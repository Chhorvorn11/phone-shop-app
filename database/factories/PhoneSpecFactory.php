<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class PhoneSpecFactory extends Factory
{
    public function definition(): array
    {
        return [
            'phone_id' => Phone::factory(),
            'label' => fake()->randomElement(['Display', 'Battery', 'Camera', 'RAM', 'Chipset']),
            'value' => fake()->word(),
        ];
    }
}
