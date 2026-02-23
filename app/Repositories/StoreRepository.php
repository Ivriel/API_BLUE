<?php

namespace App\Repositories;

use App\Interfaces\StoreRepositoryInterface;
use App\Models\Store;
use Exception;
use Illuminate\Support\Facades\DB;

class StoreRepository implements StoreRepositoryInterface
{
    public function getAll(?string $search, ?bool $isVerified, ?int $limit, bool $execute)
    {
        // buat query dimana query akan mencari user berdasarkan kolom search yang sudah didefinisikan
        $query = Store::where(function ($query) use ($isVerified, $search) {
            if ($search) {
                $query->search($search); // ini dari scope search di model User.php
            }

            if ($isVerified !== null) {
                $query->where('is_verified', $isVerified); // yang dikirim disini nanti nilai true / false
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

    public function getAllPaginated(?string $search, ?bool $isVerified, int $rowPerPage)
    {
        $query = $this->getAll(
            $search,
            $isVerified,
            null, // gapelru limit karena di method ini udah dipagination
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = Store::where('id', $id);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $store = new Store;
            $store->user_id = $data['user_id'];
            $store->name = $data['name'];
            $store->logo = $data['logo']->store('assets/store', 'public');
            $store->about = $data['about'];
            $store->phone = $data['phone'];
            $store->address_id = $data['address_id'];
            $store->city = $data['city'];
            $store->address = $data['address'];
            $store->postal_code = $data['postal_code'];
            $store->save();

            $store->storeBalance()->create(['balance' => 0]);
            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function updateVerifiedStatus(string $id, bool $isVerified)
    {
        DB::beginTransaction();
        try {
            $store = Store::findOrFail($id);
            $store->is_verified = $isVerified;
            $store->save();
            DB::commit();

            return $store;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $store = Store::findOrFail($id);
            $store->name = $data['name'];
            if (isset($data['logo'])) {
                $store->logo = $data['logo']->store('assets/store', 'public');
            }
            $store->about = $data['about'];
            $store->phone = $data['phone'];
            $store->address_id = $data['address_id'];
            $store->city = $data['city'];
            $store->address = $data['address'];
            $store->postal_code = $data['postal_code'];
            $store->save();

            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $store = Store::findOrFail($id);
            $store->delete();
            DB::commit();

            return $store;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
