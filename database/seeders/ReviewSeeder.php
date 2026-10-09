<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (Review::count()) return;
        $users = User::where('role', 'user')->get();
        foreach (Phone::all() as $phone) {
            foreach ($users->random(min(3, $users->count())) as $u) {
                Review::factory()->create(['user_id' => $u->id, 'phone_id' => $phone->id]);
            }
        }
    }
}
