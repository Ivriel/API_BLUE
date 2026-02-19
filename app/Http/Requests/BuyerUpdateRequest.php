<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'profile_picture' => 'nullable|image|mimes:png,jpg|max:2048',
            'phone_number' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'profile_picture' => 'Avatar',
            'phone_number' => 'Nomor HP',
        ];
    }
}
