<?php

namespace Database\Factories;

use App\Models\Category;
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
        return [
            'name' => $this->faker->name,
            'category_id' => Category::all()->random()->id,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->numberBetween(100,10000),
            'is_available' => true,
        ];
    }
}
