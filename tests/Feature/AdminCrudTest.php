<?php
namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Category;
use App\Models\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesUsers;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase, MakesUsers;

    public function test_admin_can_create_update_and_delete_a_category(): void
    {
        $admin = $this->admin();
        $this->actingAs($admin)->post('/admin/categories', ['name' => 'Foldable'])->assertRedirect();
        $cat = Category::where('name', 'Foldable')->firstOrFail();

        $this->actingAs($admin)->put("/admin/categories/{$cat->id}", ['name' => 'Folding'])->assertRedirect();
        $this->assertDatabaseHas('categories', ['id' => $cat->id, 'name' => 'Folding']);

        $this->actingAs($admin)->delete("/admin/categories/{$cat->id}")->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    public function test_admin_can_create_update_and_delete_a_phone(): void
    {
        $admin = $this->admin();
        $cat = Category::factory()->create();

        $this->actingAs($admin)->post('/admin/phones', [
            'category_id' => $cat->id, 'brand' => 'Nokia', 'name' => '3310', 'storage' => '1 MB', 'price' => 50, 'stock' => 9,
        ])->assertRedirect('/admin/stock');
        $phone = Phone::where('name', '3310')->firstOrFail();

        $this->actingAs($admin)->put("/admin/phones/{$phone->id}", [
            'category_id' => $cat->id, 'brand' => 'Nokia', 'name' => '3310', 'storage' => '1 MB', 'price' => 60, 'stock' => 4,
        ])->assertRedirect('/admin/stock');
        $this->assertDatabaseHas('phones', ['id' => $phone->id, 'price' => 60, 'stock' => 4]);

        $this->actingAs($admin)->delete("/admin/phones/{$phone->id}")->assertRedirect('/admin/stock');
        $this->assertDatabaseMissing('phones', ['id' => $phone->id]);
    }

    public function test_phone_validation_rejects_bad_input(): void
    {
        $this->actingAs($this->admin())->post('/admin/phones', ['brand' => '', 'price' => -5])
            ->assertSessionHasErrors(['brand', 'name', 'storage', 'price', 'stock']);
    }

    public function test_admin_can_confirm_then_cancel_a_booking(): void
    {
        $admin = $this->admin();
        $phone = Phone::factory()->create(['stock' => 5]);
        $booking = Booking::factory()->create(['phone_id' => $phone->id, 'quantity' => 2]);

        $this->actingAs($admin)->patch("/admin/bookings/{$booking->id}", ['status' => 'confirmed']);
        $this->assertSame('confirmed', $booking->fresh()->status);

        $this->actingAs($admin)->patch("/admin/bookings/{$booking->id}", ['status' => 'cancelled']);
        $this->assertSame(7, $phone->fresh()->stock);
    }

    public function test_daily_bookings_shows_only_the_chosen_date(): void
    {
        $today = Booking::factory()->create(['pickup_date' => now()->toDateString()]);
        $later = Booking::factory()->create(['pickup_date' => now()->addDays(3)->toDateString()]);

        $this->actingAs($this->admin())->get('/admin/bookings?date='.now()->toDateString())
            ->assertSee($today->user->name)->assertDontSee($later->user->name);
    }

    public function test_reports_add_up_revenue_from_completed_bookings_only(): void
    {
        $phone = Phone::factory()->create(['price' => 100]);
        Booking::factory()->create(['phone_id' => $phone->id, 'quantity' => 2, 'status' => 'completed', 'pickup_date' => now()->toDateString()]);
        Booking::factory()->create(['phone_id' => $phone->id, 'quantity' => 5, 'status' => 'pending', 'pickup_date' => now()->toDateString()]);

        $this->actingAs($this->admin())->get('/admin/reports')->assertOk()->assertSee('$200');
    }
}
