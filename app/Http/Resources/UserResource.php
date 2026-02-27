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
        $role = $this->roles->first()->name ?? '-';

        return [
            'id' => $this->id,
            'profile_picture' => asset('storage/'.$this->profile_picture),
            'name' => $this->name,
            'email' => $this->email,
            // Tambahkan tanda tanya (?->) untuk mencegah error jika user belum punya role
            'role' => $this->roles->first()?->name,
            'buyer_id' => $this->when($role === 'buyer', $this->whenLoaded('buyer', fn () => new BuyerResource($this->buyer))),
            'store_id' => $this->when($role === 'store', $this->whenLoaded('store', fn () => new StoreResource($this->store))),
            // Ambil nama permission langsung menggunakan fungsi sakti dari Spatie
            'permissions' => $this->getAllPermissions()->pluck('name'),
            'token' => $this->when(isset($this->token), $this->token),
        ];
    }
}
