<?php
namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'brand'   => fake()->company(),
            'name'    => fake()->bothify('Model ##'),
            'storage' => fake()->randomElement(['64 GB', '128 GB', '256 GB']),
            'price'   => fake()->numberBetween(150, 1500),
            'stock'   => fake()->numberBetween(5, 30),
            'description' => null,
        ];
    }
}
