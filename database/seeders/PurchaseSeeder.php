<?php
namespace Database\Seeders;

use App\Models\{Booking, Category, ContactMessage, Coupon, CustomerProfile, Payment, Phone, PhoneSpec, Purchase, PurchaseItem, Review, StockMovement, Supplier, User, Wishlist};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        if (Purchase::count()) return;
        foreach (Supplier::all() as $s) {
            $p = Purchase::factory()->create(['supplier_id' => $s->id, 'purchased_at' => now()->subDays(rand(3, 30))->toDateString()]);
            $total = 0;
            foreach (Phone::inRandomOrder()->limit(2)->get() as $phone) {
                $it = PurchaseItem::factory()->create(['purchase_id' => $p->id, 'phone_id' => $phone->id]);
                $total += $it->quantity * $it->unit_cost;
            }
            $p->update(['total_cost' => $total]);
        }
    }
}
