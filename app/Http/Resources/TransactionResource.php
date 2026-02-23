<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            'code' => $this->code,
            'buyer' => new BuyerResource($this->buyer),
            'store' => new StoreResource($this->store),
            'address_id' => $this->address_id,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'shipping' => $this->shipping,
            'shipping_type' => $this->shipping_type,
            'shipping_cost' => (float) (string) $this->shipping_cost,
            'tracking_number' => $this->tracking_number,
            'delivery_proof' => $this->delivery_proof ? asset('storage/'.$this->delivery_proof) : null,
            'delivery_status' => $this->delivery_status,
            'tax' => (float) (string) $this->tax,
            'grand_total' => (float) (string) $this->grand_total,
            'snap_token' => $this->snap_token, // untuk midtrans (payment gateway)
            'transaction_detail' => TransactionDetailResource::collection($this->transactionDetails),
            'payment_status' => $this->payment_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
