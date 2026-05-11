<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Logo Design',
                'description' => 'Produk desain logo digital untuk bisnis, brand, dan personal branding.',
            ],
            [
                'name' => 'T-Shirt Design',
                'description' => 'Desain kaos siap pakai untuk brand clothing, komunitas, dan merchandise.',
            ],
            [
                'name' => 'Poster Design',
                'description' => 'Desain poster digital untuk event, promosi, dan kampanye visual.',
            ],
            [
                'name' => 'Social Media Template',
                'description' => 'Template konten Instagram, Facebook, dan media sosial lainnya.',
            ],
            [
                'name' => 'Branding Kit',
                'description' => 'Paket aset branding untuk kebutuhan identitas visual bisnis.',
            ],
            [
                'name' => 'UI Kit',
                'description' => 'Aset UI untuk website, aplikasi, landing page, dan dashboard.',
            ],
        ];

        foreach ($categories as $category) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
