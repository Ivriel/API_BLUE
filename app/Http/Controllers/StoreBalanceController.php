<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreBalanceResource;
use App\Interfaces\StoreBalanceRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class StoreBalanceController extends Controller
{
    private StoreBalanceRepositoryInterface $storeBalanceRepository;

    public function __construct(StoreBalanceRepositoryInterface $storeBalanceRepository)
    {
        $this->storeBalanceRepository = $storeBalanceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $storeBallances = $this->storeBalanceRepository->getAll(
                $request->search,
                $request->limit,
                true,
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Dompet Toko Berhasil Diambil',
                StoreBalanceResource::collection($storeBallances),
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
            $storeBalances = $this->storeBalanceRepository->getAllPaginated(
                $request['search'] ?? null, // jadi array karena di method ini udah dipagination dan datanya memang udah banyak
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Dompet Toko Berhasil Diambil',
                PaginateResource::make($storeBalances, StoreBalanceResource::class),
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $storeBalance = $this->storeBalanceRepository->getById($id);
            if (! $storeBalance) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Dompet Toko Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Dompet Toko Berhasil Diambil',
                new StoreBalanceResource($storeBalance),
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
