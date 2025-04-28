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
        $categories = [1, 2, 3]; // Simple category IDs for demonstration

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 200),
            'category_id' => fake()->randomElement($categories),
            'sku' => 'SKU-' . strtoupper(fake()->bothify('??-####')),
        ];
    }

    /**
     * Define a product in category 1 (e.g., Electronics)
     */
    public function electronics(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => fake()->randomElement(['Smartphone', 'Laptop', 'Headphones', 'Tablet', 'Smart Watch']) . ' ' . fake()->word(),
            'category_id' => 1,
            'price' => fake()->randomFloat(2, 50, 1000),
        ]);
    }

    /**
     * Define a product in category 2 (e.g., Clothing)
     */
    public function clothing(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => fake()->randomElement(['T-shirt', 'Jeans', 'Dress', 'Jacket', 'Shoes']) . ' ' . fake()->word(),
            'category_id' => 2,
            'price' => fake()->randomFloat(2, 15, 150),
        ]);
    }

    /**
     * Define a product in category 3 (e.g., Books)
     */
    public function books(): static
    {
        return $this->state(fn(array $attributes) => [
            'name' => fake()->catchPhrase() . ' (Book)',
            'category_id' => 3,
            'price' => fake()->randomFloat(2, 8, 50),
        ]);
    }
}
