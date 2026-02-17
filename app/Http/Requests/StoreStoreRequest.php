<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'logo' => 'required|mimes:png,jpg|max:2048',
            'about' => 'required|string',
            'phone' => 'required|string',
            'address_id' => 'required',
            'city' => 'required|string',
            'address' => 'required|string',
            'postal_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'User',
            'name' => 'Nama Toko',
            'logo' => 'Logo Toko',
            'about' => 'Tentang Toko',
            'phone' => 'Nomor Telepon',
            'address_id' => 'Alamat Toko',
            'city' => 'Kota',
            'address' => 'Alamat',
            'postal_code' => 'Kode Pos',
        ];
    }
}
