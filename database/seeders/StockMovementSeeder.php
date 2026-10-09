<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StockMovementSeeder extends Seeder
{
    public function run(): void
    {
        if (StockMovement::count()) return;
        foreach (Phone::all() as $phone) {
            StockMovement::create(['phone_id' => $phone->id, 'type' => 'initial', 'quantity' => $phone->stock, 'reason' => 'Opening stock']);
        }
        foreach (PurchaseItem::with('purchase')->get() as $it) {
            StockMovement::create(['phone_id' => $it->phone_id, 'type' => 'purchase', 'quantity' => $it->quantity, 'reason' => 'Purchase '.$it->purchase->reference]);
        }
    }
}
