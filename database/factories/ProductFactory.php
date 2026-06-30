<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);
        return [
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
            'description' => fake()->sentence(),
            'full_description' => fake()->paragraph(),
            'price' => fake()->numberBetween(10000, 100000),
            'price_b2b' => fake()->numberBetween(5000, 50000),
            'min_order_b2c' => 10,
            'max_order_b2c' => 50,
            'min_order_b2b' => 1000, // 1 ton
            'max_order_b2b' => 10000, // 10 ton
            'unit' => 'kg',
            'weight' => 1,
            'stock_b2c' => 100,
            'stock_b2b' => 5000,
            'is_featured' => false,
            'is_active' => true,
            'category_id' => \App\Models\Category::factory(),
        ];
    }
}
