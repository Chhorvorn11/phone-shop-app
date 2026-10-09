<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(3),
            'subtitle' => fake()->sentence(),
            'link_url' => null,
            'image' => null,
            'sort_order' => 0,
            'active' => true,
        ];
    }
}
