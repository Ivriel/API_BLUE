<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductCategoryResource extends JsonResource
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
            // 1. Gunakan conditional null check untuk parent
            // Ini mencegah 'new Resource' dipanggil jika parent memang tidak ada
            'parent' => $this->parent_id ? new ProductCategoryResource($this->parent) : null,
            'name' => $this->name,
            'slug' => $this->slug,
            'tagline' => $this->tagline,
            'description' => $this->description,
        'product_count' => $this->whenLoaded(isset($this->products), fn () => $this->products->count()) ?? 0,
            'children_count' => $this->whenLoaded(isset($this->childrens), fn () => $this->childrens->count()) ?? 0,
            // Ubah baris ini
            'image' => $this->image ? Storage::url($this->image) : null,

            // 2. ATAU jauh lebih aman gunakan whenLoaded
            // Data 'children' hanya akan muncul jika di Controller kamu pakai ->with('childrens')
            'children' => ProductCategoryResource::collection($this->whenLoaded('childrens')),
        ];
    }
}
