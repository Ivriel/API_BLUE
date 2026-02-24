<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\WithdrawalApproveRequest;
use App\Http\Requests\WithdrawalStoreRequest;
use App\Http\Resources\PaginateResource;
use App\Http\Resources\WithdrawalResource;
use App\Interfaces\WithdrawalRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class WithdrawalController extends Controller implements HasMiddleware
{
    private WithdrawalRepositoryInterface $withdrawalRepository;

    public function __construct(WithdrawalRepositoryInterface $withdrawalRepository)
    {
        $this->withdrawalRepository = $withdrawalRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['withdrawal-list|withdrawal-create|withdrawal-edit|withdrawal-delete']), only: ['index', 'getAllPaginated', 'show']),
            new Middleware(PermissionMiddleware::using(['withdrawal-create']), only: ['withdrawal']),
            new Middleware(PermissionMiddleware::using(['withdrawal-edit']), only: ['update', 'approve']),
            new Middleware(PermissionMiddleware::using(['withdrawal-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $withdrawals = $this->withdrawalRepository->getAll(
                $request->search,
                $request->limit,
                true,
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Withdrawal Berhasil Diambil',
                WithdrawalResource::collection($withdrawals),
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
            $withdrawals = $this->withdrawalRepository->getAllPaginated(
                $request['search'] ?? null, // jadi array karena di method ini udah dipagination dan datanya memang udah banyak
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Withdrawal Berhasil Diambil',
                PaginateResource::make($withdrawals, WithdrawalResource::class),
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
    public function store(WithdrawalStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $withdrawal = $this->withdrawalRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Withdrawal Berhasil Ditambahkan',
                new WithdrawalResource($withdrawal),
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $withdrawal = $this->withdrawalRepository->getById($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Withdrawal Berhasil Diambil',
                new WithdrawalResource($withdrawal),
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

    public function approve(WithdrawalApproveRequest $request, string $id)
    {
        $request = $request->validated();

        try {
            $withdrawal = $this->withdrawalRepository->getById($id);

            if (! $withdrawal) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Withdrawal Tidak Ditemukan',
                    null,
                    404
                );
            }

            $withdrawal = $this->withdrawalRepository->approve($id, $request['proof']);

            return ResponseHelper::jsonResponse(
                true,
                'Data Withdrawal Berhasil Disetujui',
                new WithdrawalResource($withdrawal),
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
