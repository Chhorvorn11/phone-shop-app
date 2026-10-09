<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $r)
    {
        $from = ($r->date('from') ?? now()->startOfMonth())->toDateString();
        $to   = ($r->date('to') ?? now())->toDateString();
        $range = fn () => Booking::whereBetween('bookings.pickup_date', [$from, $to]);

        return view('admin.reports', [
            'from' => $from, 'to' => $to,
            'byStatus'  => $range()->select('status', DB::raw('count(*) as c'))->groupBy('status')->pluck('c', 'status'),
            'revenue'   => (int) $range()->where('bookings.status', 'completed')->sum('bookings.total_amount'),
            'collected' => (int) Payment::where('status', 'paid')->whereDate('paid_at', '>=', $from)->whereDate('paid_at', '<=', $to)->sum('amount'),
            'spent'     => (int) Purchase::whereBetween('purchased_at', [$from, $to])->sum('total_cost'),
            'top' => $range()->where('bookings.status', '!=', 'cancelled')
                ->join('phones', 'phones.id', '=', 'bookings.phone_id')
                ->select('phones.brand', 'phones.name', DB::raw('sum(bookings.quantity) as units'))
                ->groupBy('phones.id', 'phones.brand', 'phones.name')->orderByDesc('units')->limit(5)->get(),
        ]);
    }
}
