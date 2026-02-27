<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(?string $search = null, ?string $productCategoryId = null, ?int $limit = null, bool $execute = true)
    {
        $query = Product::query();

        if ($search) {
            $query->search($search);
        }

        if ($productCategoryId) {
            $query->where('product_category_id', $productCategoryId);
        }

        $query->with(['store.user', 'productCategory.parent', 'productImages', 'productReviews']);

        if ($limit) {
            $query->take($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(?string $search = null, ?string $productCategoryId = null, int $rowPerPage = 10)
    {
        $query = $this->getAll(
            $search,
            $productCategoryId,
            null, // gapelru limit karena di method ini udah dipagination
            false
        );

        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = Product::where('id', $id)->with('productImages', 'productReviews', 'productCategory', 'store');

        return $query->first();
    }

    public function getBySlug(string $slug)
    {
        $query = Product::where('slug', $slug)->with('productImages', 'productReviews', 'productCategory', 'store');

        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $product = new Product;
            $product->store_id = $data['store_id'];
            $product->product_category_id = $data['product_category_id'];
            $product->name = $data['name'];
            $product->slug = Str::slug($data['name']).'-i'.rand(100000, 999999).'.'.rand(10000000, 99999999); // agar slugnya bisa sama tapi dibelakangnya ada random string beda
            $product->price = $data['price'];
            $product->stock = $data['stock'];
            $product->weight = $data['weight'];
            $product->description = $data['description'];
            $product->save();

            $productImageRepository = new ProductImageRepository;

            if (isset($data['product_images'])) {
                foreach ($data['product_images'] as $productImage) {
                    $productImageRepository->create([
                        'product_id' => $product->id,
                        'image' => $productImage['image'],
                        'is_thumbnail' => $productImage['is_thumbnail'],
                    ]);
                }
            }

            DB::commit();

            return $product->load(['store', 'productCategory', 'productImages']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);
            $product->store_id = $data['store_id'];
            $product->product_category_id = $data['product_category_id'];
            $product->name = $data['name'];
            $product->slug = Str::slug($data['name']).'-i'.rand(100000, 999999).'.'.rand(10000000, 99999999); // agar slugnya bisa sama tapi dibelakangnya ada random string beda
            $product->price = $data['price'];
            $product->stock = $data['stock'];
            $product->weight = $data['weight'];
            $product->description = $data['description'];
            $product->save();

            $productImageRepository = new ProductImageRepository;

            if (isset($data['deleted_product_images'])) {
                foreach ($data['deleted_product_images'] as $productImage) {
                    $productImageRepository->delete($productImage);
                }
            }

            if (isset($data['product_images'])) {
                foreach ($data['product_images'] as $productImage) {
                    if (! isset($productImage['id'])) { // image yang diset adalah yanf tidak ada id nya (image baru)
                        $productImageRepository->create([
                            'product_id' => $product->id,
                            'image' => $productImage['image'],
                            'is_thumbnail' => $productImage['is_thumbnail'],
                        ]);
                    }
                }
            }

            DB::commit();

            return $product->load(['store', 'productCategory', 'productImages']);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
