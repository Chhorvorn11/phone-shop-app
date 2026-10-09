<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'amount' => fake()->numberBetween(50, 800),
            'method' => fake()->randomElement(['cash', 'card', 'transfer']),
            'status' => 'paid',
            'reference' => null,
            'paid_at' => now(),
        ];
    }
}
