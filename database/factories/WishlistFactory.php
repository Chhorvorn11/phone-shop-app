<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class WishlistFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone_id' => Phone::factory(),
        ];
    }
}
