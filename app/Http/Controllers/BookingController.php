<?php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Coupon;
use App\Models\Phone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    public function mine(Request $r)
    {
        $bookings = Booking::with(['phone', 'payments', 'coupon'])->where('user_id', $r->user()->id)->latest()->get();
        return view('my-bookings', compact('bookings'));
    }

    public function store(Request $r, Phone $phone)
    {
        $data = $r->validate([
            'quantity'    => 'required|integer|min:1|max:5',
            'pickup_date' => 'required|date|after_or_equal:today',
            'note'        => 'nullable|string|max:200',
            'coupon'      => 'nullable|string|max:30',
        ]);

        DB::transaction(function () use ($phone, $data, $r) {
            $p = Phone::lockForUpdate()->find($phone->id);
            if ($p->stock < $data['quantity']) {
                throw ValidationException::withMessages(['quantity' => "Only {$p->stock} left in stock for {$p->name}."]);
            }

            $subtotal = $p->price * $data['quantity'];
            $coupon = null;
            $discount = 0;
            if (! empty($data['coupon'])) {
                $coupon = Coupon::where('code', strtoupper(trim($data['coupon'])))->lockForUpdate()->first();
                if (! $coupon || ! $coupon->isUsable()) {
                    throw ValidationException::withMessages(['coupon' => 'That coupon is not valid.']);
                }
                $discount = intdiv($subtotal * $coupon->percent, 100);
                $coupon->increment('used_count');
            }

            $p->adjustStock(-$data['quantity'], 'booking', 'Booked by '.$r->user()->name, $r->user()->id);
            Booking::create([
                'user_id' => $r->user()->id, 'phone_id' => $p->id, 'coupon_id' => $coupon?->id,
                'quantity' => $data['quantity'], 'pickup_date' => $data['pickup_date'], 'note' => $data['note'] ?? null,
                'status' => 'pending', 'discount_amount' => $discount, 'total_amount' => $subtotal - $discount,
            ]);
        });

        return redirect()->route('bookings.mine')->with('ok', 'Booking placed. We will confirm it soon.');
    }

    public function cancel(Request $r, Booking $booking)
    {
        abort_unless($booking->user_id === $r->user()->id && $booking->status === 'pending', 403);
        $booking->changeStatus('cancelled');
        return back()->with('ok', 'Booking cancelled.');
    }
}
