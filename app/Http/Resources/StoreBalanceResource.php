<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreBalanceResource extends JsonResource
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
            'store' => new StoreResource($this->store), // akses relasi store
            'balance' => (float) (string) $this->balance, // akses relasi balance
            'store_balance_histories' => StoreBalanceHistoryResource::collection($this->whenLoaded('storeBalanceHistories')),
        ];
    }
}
