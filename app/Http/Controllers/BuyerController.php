<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\BuyerStoreRequest;
use App\Http\Requests\BuyerUpdateRequest;
use App\Http\Resources\BuyerResource;
use App\Http\Resources\PaginateResource;
use App\Interfaces\BuyerRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class BuyerController extends Controller implements HasMiddleware
{
    private BuyerRepositoryInterface $buyerRepository;

    public function __construct(BuyerRepositoryInterface $buyerRepository)
    {
        $this->buyerRepository = $buyerRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['buyer-list|buyer-create|buyer-edit|buyer-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['buyer-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['buyer-edit']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['buyer-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $buyers = $this->buyerRepository->getAll(
                $request->search,
                $request->limit,
                true,
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembeli Berhasil Diambil',
                BuyerResource::collection($buyers),
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
            $buyers = $this->buyerRepository->getAllPaginated(
                $request['search'] ?? null, // jadi array karena di method ini udah dipagination dan datanya memang udah banyak
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembeli Berhasil Diambil',
                PaginateResource::make($buyers, BuyerResource::class),
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
    public function store(BuyerStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $buyer = $this->buyerRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembeli Berhasil Ditambahkan',
                new BuyerResource($buyer),
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
            $buyer = $this->buyerRepository->getById($id);
            if (! $buyer) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Pembeli Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembeli Berhasil Diambil',
                new BuyerResource($buyer),
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
    public function update(BuyerUpdateRequest $request, string $id)
    {
        $request = $request->validated();
        try {
            $buyer = $this->buyerRepository->getById($id);
            if (! $buyer) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Pembeli Tidak Ditemukan',
                    null,
                    404
                );
            }

            $buyer = $this->buyerRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembeli Berhasil Diupdate',
                new BuyerResource($buyer),
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
            $buyer = $this->buyerRepository->getById($id);
            if (! $buyer) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Pembeli Tidak Ditemukan',
                    null,
                    404
                );
            }

            $buyer = $this->buyerRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Pembeli Berhasil Dihapus',
                new BuyerResource($buyer),
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
