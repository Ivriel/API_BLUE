<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'store_balance_id' => 'required|exists:store_balances,id',
            'amount' => 'required|min:0|integer',
            'bank_account_name' => 'required|string',
            'bank_account_number' => 'required|string',
            'bank_name' => 'required|string|in:bri,bca,mandiri,bni',
        ];
    }

    public function attributes()
    {
        return [
            'store_balance_id' => 'Dompet Toko',
            'amount' => 'Nominal',
            'bank_account_name' => 'Nama Pemilik Rekening',
            'bank_account_number' => 'Nomor Rekening',
            'bank_name' => 'Nama Bank',
        ];
    }
}
