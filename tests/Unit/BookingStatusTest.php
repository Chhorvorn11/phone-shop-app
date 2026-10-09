<?php
namespace Tests\Unit;

use App\Models\Booking;
use App\Models\Phone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_cancelling_returns_units_to_stock(): void
    {
        $phone = Phone::factory()->create(['stock' => 1]);
        $booking = Booking::factory()->create(['phone_id' => $phone->id, 'quantity' => 3]);

        $booking->changeStatus('cancelled');

        $this->assertSame(4, $phone->fresh()->stock);
    }

    public function test_cancelled_and_completed_bookings_are_final(): void
    {
        $phone = Phone::factory()->create(['stock' => 1]);
        $done = Booking::factory()->create(['phone_id' => $phone->id, 'status' => 'completed']);
        $gone = Booking::factory()->create(['phone_id' => $phone->id, 'status' => 'cancelled']);

        $done->changeStatus('cancelled');
        $gone->changeStatus('confirmed');

        $this->assertSame('completed', $done->fresh()->status);
        $this->assertSame('cancelled', $gone->fresh()->status);
        $this->assertSame(1, $phone->fresh()->stock);
    }

    public function test_cancelling_twice_only_restores_stock_once(): void
    {
        $phone = Phone::factory()->create(['stock' => 0]);
        $booking = Booking::factory()->create(['phone_id' => $phone->id, 'quantity' => 2]);

        $booking->changeStatus('cancelled');
        $booking->changeStatus('cancelled');

        $this->assertSame(2, $phone->fresh()->stock);
    }
}
