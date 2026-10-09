<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'subject' => fake()->sentence(3),
            'message' => fake()->paragraph(),
            'read_at' => null,
        ];
    }
}
