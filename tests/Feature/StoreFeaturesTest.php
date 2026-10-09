<?php
namespace Tests\Feature;

use App\Models\{Booking, Coupon, Payment, Phone, Review, Supplier};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesUsers;
use Tests\TestCase;

class StoreFeaturesTest extends TestCase
{
    use RefreshDatabase, MakesUsers;

    private function book($user, Phone $phone, array $over = [])
    {
        return $this->actingAs($user)->post("/phones/{$phone->id}/book",
            $over + ['quantity' => 1, 'pickup_date' => now()->addDay()->toDateString()]);
    }

    public function test_coupon_gives_a_discount_and_counts_a_use(): void
    {
        $phone = Phone::factory()->create(['price' => 100, 'stock' => 10]);
        $coupon = Coupon::factory()->create(['code' => 'TEN', 'percent' => 10]);

        $this->book($this->customer(), $phone, ['quantity' => 2, 'coupon' => 'ten'])->assertRedirect(route('bookings.mine'));

        $this->assertDatabaseHas('bookings', ['coupon_id' => $coupon->id, 'discount_amount' => 20, 'total_amount' => 180]);
        $this->assertSame(1, $coupon->fresh()->used_count);
    }

    public function test_expired_or_unknown_coupon_is_rejected(): void
    {
        $phone = Phone::factory()->create(['stock' => 10]);
        Coupon::factory()->create(['code' => 'OLD', 'expires_at' => now()->subDay()->toDateString()]);

        $this->book($this->customer(), $phone, ['coupon' => 'OLD'])->assertSessionHasErrors('coupon');
        $this->book($this->customer(), $phone, ['coupon' => 'NOPE'])->assertSessionHasErrors('coupon');

        $this->assertDatabaseCount('bookings', 0);
        $this->assertSame(10, $phone->fresh()->stock);
    }

    public function test_booking_writes_a_stock_log_line(): void
    {
        $phone = Phone::factory()->create(['stock' => 10]);
        $this->book($this->customer(), $phone, ['quantity' => 3]);
        $this->assertDatabaseHas('stock_movements', ['phone_id' => $phone->id, 'type' => 'booking', 'quantity' => -3]);
    }

    public function test_receiving_a_purchase_adds_stock_and_logs_it(): void
    {
        $phone = Phone::factory()->create(['stock' => 5]);
        $supplier = Supplier::factory()->create();

        $this->actingAs($this->admin())->post('/admin/purchases', [
            'supplier_id' => $supplier->id, 'purchased_at' => now()->toDateString(), 'reference' => 'PO-1',
            'items' => [['phone_id' => $phone->id, 'quantity' => 10, 'unit_cost' => 300], ['phone_id' => '', 'quantity' => '', 'unit_cost' => '']],
        ])->assertRedirect(route('admin.purchases.index'));

        $this->assertSame(15, $phone->fresh()->stock);
        $this->assertDatabaseHas('purchases', ['supplier_id' => $supplier->id, 'total_cost' => 3000]);
        $this->assertDatabaseHas('purchase_items', ['phone_id' => $phone->id, 'quantity' => 10]);
        $this->assertDatabaseHas('stock_movements', ['phone_id' => $phone->id, 'type' => 'purchase', 'quantity' => 10]);
    }

    public function test_purchase_needs_at_least_one_line(): void
    {
        $this->actingAs($this->admin())->post('/admin/purchases', [
            'supplier_id' => Supplier::factory()->create()->id, 'purchased_at' => now()->toDateString(), 'items' => [['phone_id' => '']],
        ])->assertSessionHasErrors('items');
    }

    public function test_posting_a_review_twice_keeps_one_review(): void
    {
        $user = $this->customer();
        $phone = Phone::factory()->create();

        $this->actingAs($user)->post("/phones/{$phone->id}/review", ['rating' => 2, 'comment' => 'meh']);
        $this->actingAs($user)->post("/phones/{$phone->id}/review", ['rating' => 5, 'comment' => 'great']);

        $this->assertSame(1, Review::count());
        $this->assertSame(5, Review::first()->rating);
    }

    public function test_wishlist_toggle_adds_then_removes(): void
    {
        $user = $this->customer();
        $phone = Phone::factory()->create();

        $this->actingAs($user)->post("/wishlist/{$phone->id}");
        $this->assertDatabaseHas('wishlists', ['user_id' => $user->id, 'phone_id' => $phone->id]);

        $this->actingAs($user)->post("/wishlist/{$phone->id}");
        $this->assertDatabaseMissing('wishlists', ['user_id' => $user->id, 'phone_id' => $phone->id]);
    }

    public function test_profile_can_be_saved(): void
    {
        $user = $this->customer();
        $this->actingAs($user)->put('/profile', ['phone_number' => '012 345 678', 'city' => 'Siem Reap'])->assertRedirect();
        $this->assertDatabaseHas('customer_profiles', ['user_id' => $user->id, 'city' => 'Siem Reap']);
    }

    public function test_guest_can_send_a_contact_message_and_admin_can_read_it(): void
    {
        $this->post('/contact', ['name' => 'Dara', 'email' => 'dara@example.com', 'message' => 'Do you stock Pixel 9?'])->assertRedirect(route('home'));
        $this->assertDatabaseHas('contact_messages', ['email' => 'dara@example.com', 'read_at' => null]);

        $this->actingAs($this->admin())->get('/admin/messages')->assertSee('Pixel 9');
    }

    public function test_admin_records_and_refunds_a_payment(): void
    {
        $admin = $this->admin();
        $booking = Booking::factory()->create();

        $this->actingAs($admin)->post('/admin/payments', ['booking_id' => $booking->id, 'amount' => 100, 'method' => 'cash'])->assertRedirect();
        $payment = Payment::firstOrFail();
        $this->assertSame('paid', $payment->status);

        $this->actingAs($admin)->patch("/admin/payments/{$payment->id}/refund");
        $this->assertSame('refunded', $payment->fresh()->status);
    }

    public function test_admin_can_manage_coupons_and_suppliers(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/coupons', ['code' => 'new15', 'percent' => 15, 'active' => 1])->assertRedirect();
        $this->assertDatabaseHas('coupons', ['code' => 'NEW15', 'percent' => 15, 'active' => 1]);

        $this->actingAs($admin)->post('/admin/suppliers', ['name' => 'Acme Phones'])->assertRedirect();
        $this->assertDatabaseHas('suppliers', ['name' => 'Acme Phones']);
    }

    public function test_phone_specs_are_saved_from_text(): void
    {
        $this->actingAs($this->admin())->post('/admin/phones', [
            'brand' => 'Nokia', 'name' => 'X1', 'storage' => '64 GB', 'price' => 99, 'stock' => 3,
            'specs' => "Battery: 4000 mAh\nCamera: 48 MP\nnot a spec line",
        ])->assertRedirect('/admin/stock');

        $phone = Phone::where('name', 'X1')->firstOrFail();
        $this->assertSame(2, $phone->specs()->count());
        $this->assertDatabaseHas('stock_movements', ['phone_id' => $phone->id, 'type' => 'initial', 'quantity' => 3]);
    }
}
