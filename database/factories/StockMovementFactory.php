<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'phone_id' => Phone::factory(),
            'user_id' => null,
            'type' => 'adjust',
            'quantity' => fake()->numberBetween(-5, 10),
            'reason' => fake()->sentence(3),
        ];
    }
}
