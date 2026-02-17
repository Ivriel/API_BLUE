<?php

namespace App\Repositories;

use App\Interfaces\StoreBalanceRepositoryInterface;
use App\Models\StoreBalance;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreBalanceRepository implements StoreBalanceRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        // buat query dimana query akan mencari user berdasarkan kolom search yang sudah didefinisikan
        $query = StoreBalance::where(function ($query) use ($search) {
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
        // ambil dompet toko berdasarkan id toko (store_id)
        $query = StoreBalance::where('store_id', $id);

        return $query->first();
    }

    public function credit(string $id, string $amount)
    {
        DB::beginTransaction();

        try {
            $storeBalance = StoreBalance::findOrFail($id);
            $storeBalance->balance = bcadd($storeBalance->balance, $amount, 2);
            $storeBalance->save();

            DB::commit();

            return $storeBalance;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function debit(string $id, string $amount)
    {
        DB::beginTransaction();

        try {
            $storeBalance = StoreBalance::findOrFail($id);
            if (bccomp($storeBalance->balance, $amount, 2) < 0) {
                throw new Exception('Saldo tidak mencukupi');
            }
            $storeBalance->balance = bcsub($storeBalance->balance, $amount, 2);
            $storeBalance->save();

            DB::commit();

            return $storeBalance;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
