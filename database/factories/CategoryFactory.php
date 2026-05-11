<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Logo Design',
            'T-Shirt Design',
            'Poster Design',
            'Social Media Template',
            'Branding Kit',
            'UI Kit',
            'Mockup',
            'Vector Asset',
        ]);

        return [
            'name' => $name,
            'slug' => Str::slug($name . '-' . fake()->unique()->numberBetween(100, 999)),
            'description' => fake()->sentence(12),
            'is_active' => true,
        ];
    }
}
