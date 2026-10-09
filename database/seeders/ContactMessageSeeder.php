<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        if (ContactMessage::count()) return;
        ContactMessage::factory()->count(5)->create();
        ContactMessage::oldest()->take(2)->get()->each->update(['read_at' => now()]);
    }
}
