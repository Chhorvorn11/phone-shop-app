<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['Admin', 'admin@example.com', 'admin'], ['Customer', 'user@example.com', 'user']] as [$n, $e, $r]) {
            User::firstOrNew(['email' => $e])->forceFill(['name' => $n, 'password' => Hash::make('password'), 'role' => $r, 'email_verified_at' => now()])->save();
        }
        if (User::count() < 5) {
            User::factory()->count(8)->create();
        }
    }
}
