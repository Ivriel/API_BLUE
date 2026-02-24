<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\ProductReviewStoreRequest;
use App\Http\Resources\ProductReviewResource;
use App\Interfaces\ProductReviewRepositoryInterface;
use Exception;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ProductReviewController extends Controller implements HasMiddleware
{
    private ProductReviewRepositoryInterface $productReviewRepository;

    public function __construct(ProductReviewRepositoryInterface $productReviewRepository)
    {
        $this->productReviewRepository = $productReviewRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['product-review-create']), only: ['store']),
        ];
    }

    public function store(ProductReviewStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $productReview = $this->productReviewRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Produk Review Berhasil Ditambahkan',
                new ProductReviewResource($productReview),
                201
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
}
