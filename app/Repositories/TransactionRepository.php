<?php

namespace App\Repositories;

use App\Interfaces\TransactionRepositoryInterface;
use App\Models\Product;
use App\Models\Store;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Midtrans\Config;
use Midtrans\Snap;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        // buat query dimana query akan mencari user berdasarkan kolom search yang sudah didefinisikan
        $query = Transaction::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search); // ini dari scope search di model User.php
            }
        });

        if ($limit) {
            $query->take($limit); // hanya akan mengambil sejumlah limit
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search, int $rowPerPage)
    {
        $query = $this->getAll(
            $search,
            null, // gapelru limit karena di method ini udah dipagination
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = Transaction::where('id', $id);

        return $query->first();
    }

    public function getByCode(string $code)
    {
        $query = Transaction::where('code', $code);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $transaction = new Transaction;
            $transaction->code = 'TRX-'.str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $transaction->buyer_id = $data['buyer_id'];
            $transaction->store_id = $data['store_id'];
            $transaction->address_id = $data['address_id'];
            $transaction->address = $data['address'];
            $transaction->city = $data['city'];
            $transaction->postal_code = $data['postal_code'];
            $transaction->shipping = $data['shipping'];
            $transaction->shipping_type = $data['shipping_type'];
            $transaction->shipping_cost = 0;
            $transaction->tax = 0;
            $transaction->grand_total = 0;
            $transaction->save();

            $transactionDetailRepository = new TransactionDetailRepository;
            $transactionDetails = [];

            foreach ($data['products'] as $productData) {
                $detail = $transactionDetailRepository->create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $productData['product_id'],
                    'qty' => $productData['qty'],
                ]);

                $transactionDetails[] = $detail;
            }

            $subtotal = array_reduce($transactionDetails, function ($carry, $item) {
                return $carry + $item->subtotal;
            }, 0);

            $weight = $this->getTotalWeight($transactionDetails);
            $calculation = $this->calculateShippingAndTax($data, $subtotal, $weight);

            $transaction->shipping_cost = $calculation['shipping_cost'];
            $transaction->tax = $calculation['tax'];
            $transaction->grand_total = $calculation['grand_total'];
            $transaction->save();

            Config::$serverKey = config('midtrans.serverKey');
            Config::$isProduction = config('midtrans.isProduction');
            Config::$isSanitized = config('midtrans.isSanitized');
            Config::$is3ds = config('midtrans.is3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => $transaction->code,
                    'gross_amount' => (int) $transaction->grand_total,
                ],
                'customer_details' => [
                    'first_name' => $transaction->buyer->name,
                    'email' => $transaction->buyer->email,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);
            $transaction->snap_token = $snapToken;

            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function getTotalWeight(array $transactionDetails): int
    {
        $prodyctIds = collect($transactionDetails)->pluck('product_id')->toArray(); // ambil semua produk id yang terkait
        $products = Product::whereIn('id', $prodyctIds)->get()->keyBy('id'); // ambil produk id berdasarkan id yang udah dikoleksi

        $totalWeight = 0;

        foreach ($transactionDetails as $item) {
            $product = $products[$item->product_id ?? $item['product_id']] ?? null;
            if ($product) {
                $totalWeight += $product->weight * ($item->qty ?? $item['qty']);
            }
        }

        return $totalWeight;
    }

    private function calculateShippingAndTax(array $data, float $subtotal, int $weight)
    {
        $origin = Store::findOrFail($data['store_id'])->address_id;
        $destination = $data['address_id'];

        $response = Http::withHeaders([
            'key' => 'LdxaOdZx7940f2f50621abaeBKf4oeJh',
            'Content-Type' => 'application/x-www-form-urlencoded',
        ])->asForm()->post('https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'origin' => $origin,
            'destination' => $destination,
            'weight' => $weight,
            'courier' => 'jne:sicepat:ide:sap:jnt:ninja:tiki:lion:anteraja:pos:ncs:rex:rpx:sentral:star:wahana:dse',
            'price' => 'lowest',
        ]);

        $result = $response->json();

        $shippingCost = 0;

        if (isset($result['data']) && is_array($result['data'])) {
            foreach ($result['data'] as $courier) {
                if ($courier['code'] == $data['shipping'] && $courier['service'] == $data['shipping_type']) {
                    $shippingCost = $courier['cost'];
                }
            }
        } else {
            throw new Exception('Gagal menghitung ongkos kirim: '.($result['meta']['message'] ?? 'Respons tidak valid dari server'));
        }

        return [
            'shipping_cost' => $shippingCost,
            'tax' => $subtotal * 0.11,
            'grand_total' => $subtotal * 1.11 + $shippingCost,
        ];
    }

    public function updateStatus(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);
            if (isset($data['tracking_number'])) {
                $transaction->tracking_number = $data['tracking_number'];
            }
            if (isset($data['delivery_proof'])) {
                $transaction->delivery_proof = $data['delivery_proof']->store('transaction', 'public');
            }
            $transaction->delivery_status = $data['delivery_status'];
            $transaction->save();

            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $transaction = Transaction::findOrFail($id);
            $transaction->delete();

            DB::commit();

            return $transaction;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
