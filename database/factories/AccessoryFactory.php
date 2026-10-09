<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AccessoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->words(2, true)),
            'type' => fake()->randomElement(['case', 'charger', 'cable', 'headphones', 'protector', 'other']),
            'price' => fake()->numberBetween(5, 80),
            'stock' => fake()->numberBetween(0, 50),
            'description' => fake()->sentence(),
            'image' => null,
        ];
    }
}
