<?php
namespace Database\Factories;

use App\Models\{Booking, Phone, Purchase, Supplier, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone_id' => Phone::factory(),
            'coupon_id' => null,
            'quantity' => 1,
            'pickup_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
            'note' => null,
            'discount_amount' => 0,
            'total_amount' => fn (array $a) => Phone::find($a['phone_id'])->price * $a['quantity'],
        ];
    }
}
