<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tracking_number' => 'nullable|string',
            'delivery_proof' => 'nullable|image|mimes:jpeg,png,jpg',
            'delivery_status' => 'required|in:pending,processing,delivering,completed',
        ];
    }

    public function attributes()
    {
        return [
            'tracking_number' => 'Nomor Resi',
            'delivery_proof' => 'Bukti Pengiriman',
            'delivery_status' => 'Status Pengiriman',
        ];
    }

    public function prepareForValidation() // tempat bersihkan / sesuaikan data sebelum validasi
    {
        $this->merge([
            // Menghapus spasi di awal/akhir resi sebelum dicek validasi integer
            'tracking_number' => trim($this->tracking_number),
            // Contoh: Mengubah status jadi huruf kecil semua agar cocok dengan rule 'in'
            'delivery_status' => strtolower($this->delivery_status),
        ]);
    }

    public function messages(): array
    {
        return [
            'tracking_number.required' => 'Nomor Resi Wajib Diisi',
            'tracking_number.integer' => 'Nomor Resi Harus Berupa Angka',
            'delivery_proof.required' => 'Bukti Pengiriman Wajib Diisi',
            'delivery_proof.image' => 'Bukti Pengiriman Harus Berupa Gambar',
            'delivery_proof.mimes' => 'Bukti Pengiriman Harus Berupa Gambar',
            'delivery_status.required' => 'Status Pengiriman Wajib Diisi',
            'delivery_status.in' => 'Status Pengiriman Tidak Sesuai',
        ];
    }
}
