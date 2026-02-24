<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductReviewStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'transaction_id' => 'required|string|exists:transactions,id',
            'product_id' => 'required|string|exists:products,id',
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'transaction_id' => 'ID Transaksi',
            'product_id' => 'ID Produk',
            'rating' => 'Rating',
            'review' => 'Review',
        ];
    }
}
