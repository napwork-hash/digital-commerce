<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'category' => 'Logo Design',
                'name' => 'Minimalist Logo Pack',
                'short_description' => 'Paket desain logo minimalis untuk brand modern.',
                'price' => 250000,
                'compare_at_price' => 350000,
                'file_type' => 'zip',
            ],
            [
                'category' => 'T-Shirt Design',
                'name' => 'Streetwear T-Shirt Design Bundle',
                'short_description' => 'Bundle desain kaos streetwear siap produksi.',
                'price' => 300000,
                'compare_at_price' => 450000,
                'file_type' => 'zip',
            ],
            [
                'category' => 'Social Media Template',
                'name' => 'Instagram Business Template Kit',
                'short_description' => 'Template Instagram carousel untuk promosi bisnis.',
                'price' => 150000,
                'compare_at_price' => 250000,
                'file_type' => 'zip',
            ],
            [
                'category' => 'Branding Kit',
                'name' => 'Complete Branding Starter Kit',
                'short_description' => 'Paket branding awal untuk UMKM dan startup.',
                'price' => 500000,
                'compare_at_price' => 750000,
                'file_type' => 'zip',
            ],
            [
                'category' => 'UI Kit',
                'name' => 'SaaS Landing Page UI Kit',
                'short_description' => 'UI kit modern untuk landing page SaaS dan startup.',
                'price' => 400000,
                'compare_at_price' => 600000,
                'file_type' => 'fig',
            ],
        ];

        foreach ($products as $product) {
            $category = Category::query()
                ->where('name', $product['category'])
                ->first();

            if (!$category) {
                continue;
            }

            Product::query()->updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'short_description' => $product['short_description'],
                    'description' => $product['short_description'] . ' File digital akan tersedia setelah pembayaran berhasil.',
                    'price' => $product['price'],
                    'compare_at_price' => $product['compare_at_price'],
                    'preview_image' => 'https://placehold.co/800x600?text=' . urlencode($product['name']),
                    'gallery' => [
                        'https://placehold.co/800x600?text=Preview+1',
                        'https://placehold.co/800x600?text=Preview+2',
                        'https://placehold.co/800x600?text=Preview+3',
                    ],
                    'digital_file_path' => 'products/' . Str::slug($product['name']) . '.zip',
                    'file_type' => $product['file_type'],
                    'file_size' => 2048000,
                    'is_active' => true,
                    'is_featured' => true,
                ]
            );
        }

        Product::factory()
            ->count(20)
            ->create();
    }
}
