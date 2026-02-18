<?php

use App\Http\Controllers\StoreBalanceController;
use App\Http\Controllers\StoreBalanceHistoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
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
