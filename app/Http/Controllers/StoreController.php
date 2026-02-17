<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreStoreRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\StoreResource;
use App\Interfaces\StoreRepositoryInterface;
use Exception;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private StoreRepositoryInterface $storeRepository;

    public function __construct(StoreRepositoryInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $stores = $this->storeRepository->getAll(
                $request->search,
                $request->is_verified,
                $request->limit,
                true,
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Toko Berhasil Diambil',
                StoreResource::collection($stores),
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
            'is_verified' => 'nullable|boolean',
            'row_per_page' => 'required|integer',
        ]);

        try {
            $stores = $this->storeRepository->getAllPaginated(
                $request['search'] ?? null, // jadi array karena di method ini udah dipagination dan datanya memang udah banyak
                $request['is_verified'] ?? null,
                $request['row_per_page'],
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Toko Berhasil Diambil',
                PaginateResource::make($stores, StoreResource::class),
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
    public function store(StoreStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $store = $this->storeRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Toko Berhasil Ditambahkan',
                new StoreResource($store),
                201
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $store = $this->storeRepository->getById($id);
            if (! $store) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Toko Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Toko Berhasil Diambil',
                new StoreResource($store),
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

    public function updateVerifiedStatus(string $id)
    {
        try {
            $store = $this->storeRepository->getById($id);
            if (! $store) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Toko Tidak Ditemukan',
                    null,
                    404
                );
            }

            $store = $this->storeRepository->updateVerifiedStatus($id, true);

            return ResponseHelper::jsonResponse(
                true,
                'Data Toko Berhasil Diverifikasi',
                new StoreResource($store),
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
    public function update(StoreUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $store = $this->storeRepository->getById($id);
            if (! $store) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Toko Tidak Ditemukan',
                    null,
                    404
                );
            }

            $store = $this->storeRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Toko Berhasil Diupdate',
                new StoreResource($store),
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
            $store = $this->storeRepository->getById($id);
            if (! $store) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Toko Tidak Ditemukan',
                    null,
                    404
                );
            }

            $store = $this->storeRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Toko Berhasil Dihapus',
                new StoreResource($store),
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
