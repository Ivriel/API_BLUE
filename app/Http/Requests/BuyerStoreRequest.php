<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'profile_picture' => 'required|image|mimes:png,jpg|max:2048',
            'phone_number' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'User',
            'profile_picture' => 'Avatar',
            'phone_number' => 'Nomor HP',
        ];
    }
}
