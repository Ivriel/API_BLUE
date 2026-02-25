<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email|string',
            'password' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'Kata Sandi',
        ];
    }
}
