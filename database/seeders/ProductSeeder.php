<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat products dalam batch kecil untuk menghindari SSL timeout
        $batchSize = 10;
        $totalProducts = 100;

        for ($i = 0; $i < $totalProducts; $i += $batchSize) {
            DB::transaction(function () use ($batchSize) {
                $products = Product::factory()->count($batchSize)->create();

                $images = [];
                foreach ($products as $product) {
                    // buat 1-5 images untuk setiap produk
                    $imageCount = rand(1, 5);

                    // thumbnail
                    $images[] = ProductImage::factory()->thumbnail()->make([
                        'product_id' => $product->id,
                    ])->toArray();

                    // additional images
                    for ($j = 1; $j < $imageCount; $j++) {
                        $images[] = ProductImage::factory()->make([
                            'product_id' => $product->id,
                        ])->toArray();
                    }
                }

                // Batch insert semua images sekaligus
                ProductImage::insert($images);
            });
        }
    }
}
