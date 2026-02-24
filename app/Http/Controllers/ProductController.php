<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ProductController extends Controller implements HasMiddleware
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['product-list|product-create|product-edit|product-delete']), only: ['index', 'getAllPaginated', 'show', 'showBySlug']),
            new Middleware(PermissionMiddleware::using(['product-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['product-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['product-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $products = $this->productRepository->getAll(
                $request->search,
                $request->product_category_id,
                $request->limit ?? 20,
                true,
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Kategori Produk Berhasil Diambil',
                ProductResource::collection($products),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    public function getAllPaginated(Request $request)
    {

        $validated = $request->validate([
            'search' => 'nullable|string',
            'product_category_id' => 'nullable|string|exists:product_categories,id',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $products = $this->productRepository->getAllPaginated(
                $validated['search'] ?? null,
                $validated['product_category_id'] ?? null,
                $validated['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Produk Berhasil Diambil',
                PaginateResource::make($products, ProductResource::class),
                200
            );
        } catch (Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $product = $this->productRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Produk berhasil ditambahkan',
                new ProductResource($product),
                201
            );
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = $this->productRepository->getById($id);
            if (! $product) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Produk Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Produk Berhasil Diambil',
                new ProductResource($product),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    public function showBySlug(string $slug)
    {
        try {
            $product = $this->productRepository->getBySlug($slug);
            if (! $product) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Produk Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Produk Berhasil Diambil',
                new ProductResource($product),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $product = $this->productRepository->getById($id);
            if (! $product) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Produk Tidak Ditemukan',
                    null,
                    404
                );
            }

            $product = $this->productRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Produk Berhasil Diperbarui',
                new ProductResource($product),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $product = $this->productRepository->getById($id);
            if (! $product) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Produk Tidak Ditemukan',
                    null,
                    404
                );
            }

            $product = $this->productRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Produk Berhasil Dihapus',
                new ProductResource($product),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
