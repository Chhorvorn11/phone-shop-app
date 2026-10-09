<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PhoneSpecSeeder extends Seeder
{
    public function run(): void
    {
        if (PhoneSpec::count()) return;
        foreach (Phone::all() as $i => $phone) {
            foreach (['Display' => ($i % 2 ? '6.7 in AMOLED' : '6.1 in OLED'), 'Battery' => (3800 + $i * 200).' mAh', 'Camera' => (48 + ($i % 3) * 2).' MP'] as $label => $value) {
                PhoneSpec::create(['phone_id' => $phone->id, 'label' => $label, 'value' => $value]);
            }
        }
    }
}
