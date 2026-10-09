<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_id' => Supplier::factory(),
            'reference' => fake()->bothify('PO-####'),
            'purchased_at' => now()->toDateString(),
            'total_cost' => 0,
            'note' => null,
        ];
    }
}
