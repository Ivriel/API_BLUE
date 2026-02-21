<?php

namespace Database\Factories;

use App\Helpers\ImageHelper\ImageHelper;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $imageHelper = new ImageHelper;

        return [
            'id' => (string) Str::uuid(),
            'product_id' => Product::factory(),
            'image' => $imageHelper->storeAndResizeImage(
                $imageHelper->createDummyImageWithTextSizeAndPosition(
                    800, 800, 'center', 'center', 'random', 'large'),
                'product',
                800,
                800
            ),
            'is_thumbnail' => false,
        ];
    }

    public function thumbnail(): static // agar gambar id pertama yang jadi thumbnail
    {
        return $this->state(fn (array $attributes) => [
            'is_thumbnail' => true,
        ]);
    }
}
