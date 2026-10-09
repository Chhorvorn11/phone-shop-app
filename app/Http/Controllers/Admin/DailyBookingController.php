<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DailyBookingController extends Controller
{
    public function index(Request $r)
    {
        $date = $r->date('date') ?? now();
        $bookings = Booking::with(['user', 'phone'])->whereDate('pickup_date', $date)->orderBy('status')->get();
        return view('admin.daily_bookings', ['bookings' => $bookings, 'date' => $date->toDateString()]);
    }

    public function update(Request $r, Booking $booking)
    {
        $status = $r->validate(['status' => 'required|in:confirmed,completed,cancelled'])['status'];
        $booking->changeStatus($status);
        return back()->with('ok', "Booking marked {$status}.");
    }
}
