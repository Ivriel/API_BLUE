<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\LoginStoreRequest;
use App\Http\Requests\UserRegisterStoreRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthRepositoryInterface;
use Exception;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(UserRegisterStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $user = $this->authRepository->register($request);

            return ResponseHelper::jsonResponse(
                true,
                'Registrasi Berhasil',
                UserResource::make($user),
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

    public function login(LoginStoreRequest $request)
    {
        $request = $request->validated();

        try {
            $user = $this->authRepository->login($request);

            return ResponseHelper::jsonResponse(
                true,
                'Login Berhasil',
                UserResource::make($user),
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

    public function getProfile()
    {
        try {
            $user = $this->authRepository->getProfile();

            return ResponseHelper::jsonResponse(
                true,
                'Data Profile Berhasil Diambil',
                UserResource::make($user),
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

    public function logout()
    {
        try {
            $user = $this->authRepository->logout();

            return ResponseHelper::jsonResponse(
                true,
                'Logout Berhasil',
                new UserResource($user),
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
}
