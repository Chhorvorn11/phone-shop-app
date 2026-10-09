<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'purchase_id' => Purchase::factory(),
            'phone_id' => Phone::factory(),
            'quantity' => fake()->numberBetween(5, 20),
            'unit_cost' => fake()->numberBetween(100, 900),
        ];
    }
}
