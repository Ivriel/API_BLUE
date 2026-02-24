<?php

use App\Http\Controllers\BuyerController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\StoreBalanceController;
use App\Http\Controllers\StoreBalanceHistoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::apiResource('user', UserController::class);
Route::get('user/all/paginated', [UserController::class, 'getAllPaginated']);

Route::apiResource('store', StoreController::class);
Route::get('store/all/paginated', [StoreController::class, 'getAllPaginated']);
Route::post('store/{id}/verified', [storeController::class, 'updateVerifiedStatus']);

Route::apiResource('store-balance', StoreBalanceController::class)->except('store', 'update', 'destroy');
Route::get('store-balance/all/paginated', [StoreBalanceController::class, 'getAllPaginated']);

Route::apiResource('store-balance-history', StoreBalanceHistoryController::class)->except('store', 'update', 'destroy');
Route::get('store-balance-history/all/paginated', [StoreBalanceHistoryController::class, 'getAllPaginated']);

Route::apiResource('withdrawal', WithdrawalController::class)->except('update', 'destroy');
Route::get('withdrawal/all/paginated', [WithdrawalController::class, 'getAllPaginated']);
Route::post('withdrawal/{id}/approve', [WithdrawalController::class, 'approve']);

Route::apiResource('buyer', BuyerController::class);
Route::get('buyer/all/paginated', [BuyerController::class, 'getAllPaginated']);

Route::apiResource('product-category', ProductCategoryController::class);
Route::get('product-category/all/paginated', [ProductCategoryController::class, 'getAllPaginated']);
Route::get('product-category/slug/{slug}', [ProductCategoryController::class, 'showBySlug']);

Route::apiResource('product', ProductController::class);
Route::get('product/all/paginated', [ProductController::class, 'getAllPaginated']);
Route::get('product/slug/{slug}', [ProductController::class, 'showBySlug']);

Route::apiResource('transaction', TransactionController::class);
Route::get('transaction/all/paginated', [TransactionController::class, 'getAllPaginated']);
Route::get('transaction/code/{code}', [TransactionController::class, 'showByCode']);

Route::post('product-review', [ProductReviewController::class, 'store']);
