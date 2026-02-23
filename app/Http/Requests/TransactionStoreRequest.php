<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'buyer_id' => 'required|string|exists:buyers,id',
            'store_id' => 'required|string|exists:stores,id',
            'address_id' => 'required|integer',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'shipping' => 'required',
            'shipping_type' => 'required',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|string|exists:products,id',
            'products.*.qty' => 'required|integer|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'buyer_id' => 'Pembeli',
            'store_id' => 'Toko',
            'address_id' => 'Alamat',
            'address' => 'Alamat',
            'city' => 'Kota',
            'postal_code' => 'Kode Pos',
            'shipping' => 'Pengiriman',
            'shipping_type' => 'Jenis Pengiriman',
        ];
    }

    public function messages()
    {
        return [
            'buyer_id.required' => 'Pembeli wajib diisi',
            'buyer_id.exists' => 'Pembeli tidak ditemukan',
            'store_id.required' => 'Toko wajib diisi',
            'store_id.exists' => 'Toko tidak ditemukan',
            'address_id.required' => 'Alamat wajib diisi',
            'address_id.integer' => 'Alamat harus berupa angka',
            'address.required' => 'Alamat wajib diisi',
            'address.string' => 'Alamat harus berupa string',
            'city.required' => 'Kota wajib diisi',
            'city.string' => 'Kota harus berupa string',
            'postal_code.required' => 'Kode Pos wajib diisi',
            'postal_code.string' => 'Kode Pos harus berupa string',
            'shipping.required' => 'Pengiriman wajib diisi',
            'shipping.string' => 'Pengiriman harus berupa string',
            'shipping_type.required' => 'Jenis Pengiriman wajib diisi',
            'shipping_type.string' => 'Jenis Pengiriman harus berupa string',
            'products.required' => 'Produk wajib diisi',
            'products.array' => 'Produk harus berupa array',
            'products.min' => 'Produk minimal 1',
            'products.*.product_id.required' => 'Produk wajib diisi',
            'products.*.product_id.string' => 'Produk harus berupa string',
            'products.*.product_id.exists' => 'Produk tidak ditemukan',
            'products.*.qty.required' => 'Jumlah wajib diisi',
            'products.*.qty.integer' => 'Jumlah harus berupa angka',
            'products.*.qty.min' => 'Jumlah minimal 1',
        ];
    }
}
