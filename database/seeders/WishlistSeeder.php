<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $phones = Phone::all();
        foreach (User::where('role', 'user')->get() as $u) {
            foreach ($phones->random(2) as $phone) {
                Wishlist::firstOrCreate(['user_id' => $u->id, 'phone_id' => $phone->id]);
            }
        }
    }
}
