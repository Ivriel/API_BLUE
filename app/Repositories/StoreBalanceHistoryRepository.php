<?php

namespace App\Repositories;

use App\Interfaces\StoreBalanceHistoryRepositoryInterface;
use App\Models\StoreBalanceHistory;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreBalanceHistoryRepository implements StoreBalanceHistoryRepositoryInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        // buat query dimana query akan mencari user berdasarkan kolom search yang sudah didefinisikan
        $query = StoreBalanceHistory::where(function ($query) use ($search) {
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
        $query = StoreBalanceHistory::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $storeBalanceHistory = new StoreBalanceHistory;
            $storeBalanceHistory->store_balance_id = $data['store_balance_id'];
            $storeBalanceHistory->type = $data['type'];
            $storeBalanceHistory->reference_id = $data['reference_id'];
            $storeBalanceHistory->reference_type = $data['reference_type'];
            $storeBalanceHistory->amount = $data['amount'];
            $storeBalanceHistory->remarks = $data['remarks'];
            $storeBalanceHistory->save();

            DB::commit();

            return $storeBalanceHistory;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $storeBalanceHistory = StoreBalanceHistory::findOrFail($id);
            $storeBalanceHistory->type = $data['type'];
            $storeBalanceHistory->reference_id = $data['reference_id'];
            $storeBalanceHistory->reference_type = $data['reference_type'];
            $storeBalanceHistory->amount = $data['amount'];
            $storeBalanceHistory->remarks = $data['remarks'];
            $storeBalanceHistory->save();

            DB::commit();

            return $storeBalanceHistory;
        } catch (\Exception $e) {
            DB::rollBack();

            throw new Exception($e->getMessage());
        }
    }
}
