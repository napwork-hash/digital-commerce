<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Minimalist Logo Pack',
            'Modern T-Shirt Design Bundle',
            'Instagram Carousel Template',
            'Business Branding Kit',
            'Creative Poster Design',
            'Premium UI Landing Page Kit',
            'Streetwear Mockup Pack',
            'Vector Icon Collection',
        ]) . ' ' . fake()->unique()->numberBetween(100, 999);

        $price = fake()->randomElement([
            250000,
            350000,
            500000,
            750000,
            1000000,
        ]);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'short_description' => fake()->sentence(12),
            'description' => fake()->paragraphs(3, true),

            'price' => $price,
            'compare_at_price' => $price + fake()->randomElement([50000, 100000, 150000]),

            'preview_image' => 'https://placehold.co/800x600?text=' . urlencode($name),
            'gallery' => [
                'https://placehold.co/800x600?text=Preview+1',
                'https://placehold.co/800x600?text=Preview+2',
                'https://placehold.co/800x600?text=Preview+3',
            ],

            'digital_file_path' => 'products/dummy-file.zip',
            'file_type' => 'zip',
            'file_size' => fake()->numberBetween(1_000_000, 50_000_000),

            'is_active' => true,
            'is_featured' => fake()->boolean(30),
        ];
    }
}
