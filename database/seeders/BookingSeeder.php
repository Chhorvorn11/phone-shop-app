<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        if (Booking::count()) return;
        $users = User::where('role', 'user')->get();
        $phones = Phone::all();
        foreach (range(1, 14) as $i) {
            $phone = $phones->random();
            $qty = rand(1, 2);
            Booking::create([
                'user_id' => $users->random()->id, 'phone_id' => $phone->id, 'quantity' => $qty,
                'pickup_date' => $i === 1 ? now()->toDateString() : now()->addDays(rand(-6, 4))->toDateString(),
                'status' => $i === 1 ? 'pending' : fake()->randomElement(['pending', 'confirmed', 'completed', 'completed', 'cancelled']),
                'total_amount' => $phone->price * $qty,
            ]);
        }
    }
}
