<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        if (Payment::count()) return;
        foreach (Booking::whereIn('status', ['confirmed', 'completed'])->get() as $b) {
            Payment::factory()->create([
                'booking_id' => $b->id,
                'amount' => $b->status === 'completed' ? $b->total_amount : intdiv($b->total_amount, 2),
            ]);
        }
    }
}
