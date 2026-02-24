<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBalanceHistoryResource;
use App\Interfaces\StoreBalanceHistoryRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class StoreBalanceHistoryController extends Controller implements HasMiddleware
{
    private StoreBalanceHistoryRepositoryInterface $storeBalanceHistoryRepository;

    public function __construct(StoreBalanceHistoryRepositoryInterface $storeBalanceHistoryRepository)
    {
        $this->storeBalanceHistoryRepository = $storeBalanceHistoryRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['store-balance-history-list']), only: ['index', 'getAllPaginated', 'show']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $storeBalanceHistories = $this->storeBalanceHistoryRepository->getAll(
                $request->search,
                $request->limit,
                true,
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Riwayat Dompet Toko Berhasil Diambil',
                StoreBalanceHistoryResource::collection($storeBalanceHistories),
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
        $request = $request->validate([
            'search' => 'nullable|string',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $storeBalanceHistories = $this->storeBalanceHistoryRepository->getAllPaginated(
                $request['search'] ?? null, // jadi array karena di method ini udah dipagination dan datanya memang udah banyak
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Riwayat Dompet Toko Berhasil Diambil',
                PaginateResource::make($storeBalanceHistories, StoreBalanceHistoryResource::class),
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $storeBalanceHistory = $this->storeBalanceHistoryRepository->getById($id);
            if (! $storeBalanceHistory) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Riwayat Dompet Toko Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Riwayat Dompet Toko Diambil',
                new StoreBalanceHistoryResource($storeBalanceHistory),
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
}
