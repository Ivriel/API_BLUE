<?php

namespace App\Repositories;

use App\Interfaces\ProductCategoryRepositoryInterface;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryRepository implements ProductCategoryRepositoryInterface
{
    public function getAll(?string $search = null, ?bool $isParent = null, ?int $limit = null, bool $execute = true)
    {
        // buat query dimana query akan mencari user berdasarkan kolom search yang sudah didefinisikan
        $query = ProductCategory::where(function ($query) use ($isParent, $search) {
            if ($search) {
                $query->search($search); // ini dari scope search di model User.php
            }

            if ($isParent === true) {
                $query->whereNull('parent_id'); // mencari dimana parent id nya adalah null
            }
        })->withCount(['products', 'childrens'])->with('childrens');

        if ($limit) {
            $query->take($limit); // hanya akan mengambil sejumlah limit
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search = null, ?bool $isParent = null, int $rowPerPage = 10)
    {
        $query = $this->getAll(
            $search,
            $isParent,
            null, // gapelru limit karena di method ini udah dipagination
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = ProductCategory::where('id', $id)->with('childrens')->withCount(['products', 'childrens']);

        return $query->first();
    }

    public function getBySlug(string $slug)
    {
        $query = ProductCategory::where('slug', $slug)->with('childrens')->withCount(['products', 'childrens']);

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $productCategory = new ProductCategory;
            if (isset($data['parent_id'])) {
                $productCategory->parent_id = $data['parent_id'];
            }
            if (isset($data['image'])) {
                $productCategory->image = $data['image']->store('assets/product-category', 'public');
            }
            $productCategory->name = $data['name'];
            $productCategory->slug = Str::slug($data['name']);
            if (isset($data['tagline'])) {
                $productCategory->tagline = $data['tagline'];
            }
            $productCategory->description = $data['description'];
            $productCategory->save();

            $productCategory->loadCount(['products', 'childrens']);

            DB::commit();

            return $productCategory;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $productCategory = ProductCategory::findOrFail($id);
            if (isset($data['parent_id'])) {
                $productCategory->parent_id = $data['parent_id'];
            }
            if (isset($data['image'])) {
                $productCategory->image = $data['image']->store('assets/product-category', 'public');
            }
            $productCategory->name = $data['name'];
            $productCategory->slug = Str::slug($data['name']);
            if (isset($data['tagline'])) {
                $productCategory->tagline = $data['tagline'];
            }
            $productCategory->description = $data['description'];
            $productCategory->save();

            $productCategory->loadCount(['products', 'childrens']);

            DB::commit();

            return $productCategory;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->delete();
            DB::commit();

            return $productCategory;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
