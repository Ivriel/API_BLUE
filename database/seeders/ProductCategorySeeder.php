<?php

namespace Database\Seeders;

use App\Helpers\ImageHelper\ImageHelper;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductCategorySeeder extends Seeder
{
    protected $model = ProductCategory::class;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'tagline' => 'Temukan berbagai produk elektronik terbaik',
                'description' => 'Kategori produk elektronik seperti smartphone, laptop, dan gadget lainnya',
                'children' => [
                    [
                        'name' => 'Smartphone',
                        'tagline' => 'Smartphone terbaru dengan teknologi canggih',
                        'description' => 'Berbagai merek smartphone terbaru dengan spesifikasi tinggi',
                    ],
                    [
                        'name' => 'Laptop',
                        'tagline' => 'Laptop untuk produktivitas kerja',
                        'description' => 'Koleksi laptop untuk gaming, kerja, dan kebutuhan sehari-hari',
                    ],
                    [
                        'name' => 'Aksesoris Gadget',
                        'tagline' => 'Lengkapi gadget Anda dengan aksesoris terbaik',
                        'description' => 'Berbagai aksesoris untuk smartphone dan laptop',
                    ],
                ],
            ],
            [
                'name' => 'Fashion',
                'tagline' => 'Temukan gaya fashion terbaik Anda',
                'description' => 'Kategori fashion untuk pria dan wanita',
                'children' => [
                    [
                        'name' => 'Pakaian Pria',
                        'tagline' => 'Koleksi pakaian pria terkini',
                        'description' => 'Berbagai pakaian pria untuk berbagai kesempatan',
                    ],
                    [
                        'name' => 'Pakaian Wanita',
                        'tagline' => 'Koleksi pakaian wanita terkini',
                        'description' => 'Berbagai pakaian wanita untuk berbagai kesempatan',
                    ],
                ],
            ],
        ];

        $imageHelper = new ImageHelper;

        foreach ($categories as $category) {
            $parent = ProductCategory::create([
                'id' => (string) Str::uuid(), // Isi manual di sini
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'tagline' => $category['tagline'],
                'description' => $category['description'],
                'image' => $imageHelper->storeAndResizeImage(
                    $imageHelper->createDummyImageWithTextSizeAndPosition(250, 250, 'center', 'center', 'random', 'medium'),
                    'product-category',
                    250,
                    250
                ),
                'parent_id' => null,
            ]);

            foreach ($category['children'] as $child) {
                ProductCategory::create([
                    'id' => (string) Str::uuid(), // Isi manual di sini
                    'name' => $child['name'],
                    'slug' => Str::slug($child['name']),
                    'tagline' => $child['tagline'],
                    'description' => $child['description'],
                    'image' => $imageHelper->storeAndResizeImage(
                        $imageHelper->createDummyImageWithTextSizeAndPosition(250, 250, 'center', 'center', 'random', 'medium'),
                        'product-category',
                        250,
                        250
                    ),
                    'parent_id' => $parent->id,
                ]);
            }
        }
    }
}
