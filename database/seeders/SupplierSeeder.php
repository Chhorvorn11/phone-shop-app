<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        if (Supplier::count()) return;
        foreach (['Phone Wholesale Ltd', 'Angkor Mobile Supply', 'Mekong Devices'] as $n) {
            Supplier::factory()->create(['name' => $n]);
        }
    }
}
