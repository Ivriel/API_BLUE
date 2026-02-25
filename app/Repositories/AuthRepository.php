<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
    public function register(array $data)
    {
        DB::beginTransaction();

        try {
            $user = new User;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = bcrypt($data['password']);
            $user->save();

            $user->assignRole($data['role']);

            if ($data['role'] == 'buyer') {
                $user->buyer()->create([
                    'profile_picture' => null,
                    'phone_number' => null,
                ]);
            }

            $user->refresh();
            $user->token = $user->createToken('auth_token', ['*'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function login(array $data)
    {
        DB::beginTransaction();

        try {
            if (! Auth::guard('web')->attempt($data)) {
                throw new Exception('Unauthorized');
            }
            $user = Auth::user();
            $user->token = $user->createToken('auth_token', ['*'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;

            DB::commit();

            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getProfile()
    {
        try {
            if (! Auth::check()) {
                throw new Exception('Unauthorized');
            }
            $user = Auth::user();

            return $user;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            if (! Auth::check()) {
                throw new Exception('Unauthorized');
            }
            $user = Auth::user();
            $user->tokens()->delete();

            return $user;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
