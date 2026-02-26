<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // untuk memformat data apa aja yang akan ditampilkan
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            // Tambahkan tanda tanya (?->) untuk mencegah error jika user belum punya role
            'role' => $this->roles->first()?->name,
            'buyer_id' => $this->buyer?->id,
            'store_id' => $this->store?->id,

            // Ambil nama permission langsung menggunakan fungsi sakti dari Spatie
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'token' => $this->token,
        ];
    }
}
