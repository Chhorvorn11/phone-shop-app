<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        return view('admin.payments', [
            'payments' => Payment::with('booking.user', 'booking.phone')->latest()->paginate(15),
            'bookings' => Booking::with(['user', 'phone'])->where('status', '!=', 'cancelled')->latest()->limit(60)->get(),
        ]);
    }

    public function store(Request $r)
    {
        $d = $r->validate(['booking_id' => 'required|exists:bookings,id', 'amount' => 'required|integer|min:1', 'method' => 'required|in:cash,card,transfer', 'reference' => 'nullable|max:60']);
        Payment::create($d + ['status' => 'paid', 'paid_at' => now()]);
        return back()->with('ok', 'Payment recorded.');
    }

    public function refund(Payment $payment)
    {
        $payment->update(['status' => 'refunded']);
        return back()->with('ok', 'Payment marked as refunded.');
    }
}
