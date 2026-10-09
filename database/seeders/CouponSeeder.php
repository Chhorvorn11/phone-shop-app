<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([['WELCOME10', 10, null, true], ['SAVE20', 20, 50, true], ['OLD5', 5, null, false]] as [$code, $pct, $max, $on]) {
            Coupon::firstOrCreate(['code' => $code], ['percent' => $pct, 'max_uses' => $max, 'active' => $on, 'expires_at' => now()->addMonths(3)->toDateString()]);
        }
    }
}
