<?php
namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\MakesUsers;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase, MakesUsers;

    private function book($user, Phone $phone, array $over = [])
    {
        return $this->actingAs($user)->post("/phones/{$phone->id}/book",
            $over + ['quantity' => 1, 'pickup_date' => now()->addDay()->toDateString()]);
    }

    public function test_booking_takes_units_out_of_stock(): void
    {
        $user = $this->customer();
        $phone = Phone::factory()->create(['stock' => 10]);

        $this->book($user, $phone, ['quantity' => 3])->assertRedirect(route('bookings.mine'));

        $this->assertSame(7, $phone->fresh()->stock);
        $this->assertDatabaseHas('bookings', ['user_id' => $user->id, 'phone_id' => $phone->id, 'quantity' => 3, 'status' => 'pending']);
    }

    public function test_cannot_book_more_than_is_in_stock(): void
    {
        $phone = Phone::factory()->create(['stock' => 2]);

        $this->book($this->customer(), $phone, ['quantity' => 3])->assertSessionHasErrors('quantity');

        $this->assertSame(2, $phone->fresh()->stock);
        $this->assertDatabaseCount('bookings', 0);
    }

    public function test_pickup_date_cannot_be_in_the_past(): void
    {
        $phone = Phone::factory()->create();
        $this->book($this->customer(), $phone, ['pickup_date' => now()->subDay()->toDateString()])->assertSessionHasErrors('pickup_date');
    }

    public function test_guests_cannot_book(): void
    {
        $phone = Phone::factory()->create();
        $this->post("/phones/{$phone->id}/book", ['quantity' => 1, 'pickup_date' => now()->addDay()->toDateString()])->assertRedirect('/login');
    }

    public function test_cancelling_a_pending_booking_returns_stock(): void
    {
        $user = $this->customer();
        $phone = Phone::factory()->create(['stock' => 5]);
        $booking = Booking::factory()->create(['user_id' => $user->id, 'phone_id' => $phone->id, 'quantity' => 2]);

        $this->actingAs($user)->patch("/my-bookings/{$booking->id}/cancel")->assertRedirect();

        $this->assertSame(7, $phone->fresh()->stock);
        $this->assertSame('cancelled', $booking->fresh()->status);
    }

    public function test_cannot_cancel_someone_elses_booking(): void
    {
        $booking = Booking::factory()->create();
        $this->actingAs($this->customer())->patch("/my-bookings/{$booking->id}/cancel")->assertForbidden();
    }

    public function test_my_bookings_only_lists_my_own(): void
    {
        $me = $this->customer();
        $mine = Booking::factory()->create(['user_id' => $me->id]);
        $other = Booking::factory()->create();

        $this->actingAs($me)->get('/my-bookings')->assertSee($mine->phone->name)->assertDontSee($other->phone->name);
    }
}
