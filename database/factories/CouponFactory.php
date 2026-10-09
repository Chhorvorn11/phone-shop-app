<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('SAVE##??')),
            'percent' => fake()->randomElement([5, 10, 15, 20]),
            'expires_at' => now()->addMonths(2)->toDateString(),
            'max_uses' => null,
            'used_count' => 0,
            'active' => true,
        ];
    }
}
