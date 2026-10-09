<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerProfileSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $u) {
            if (! CustomerProfile::where('user_id', $u->id)->exists()) {
                CustomerProfile::factory()->create(['user_id' => $u->id]);
            }
        }
    }
}
