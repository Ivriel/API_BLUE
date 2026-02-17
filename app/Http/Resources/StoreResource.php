<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->user), // this->user itu dari relasi one to one store dengan user
            'name' => $this->name,
            'logo' => asset('storage/'.$this->logo),
            'about' => $this->about,
            'phone' => $this->phone,
            'address_id' => $this->address_id, // ambil dari api raja ongkir
            'city' => $this->city,
            'address' => $this->address,
            'postal_code' => $this->postal_code,
            'is_verified' => $this->is_verified,
        ];
    }
}
