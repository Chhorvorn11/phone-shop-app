<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Phone;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'phones'     => Phone::count(),
            'categories' => Category::count(),
            'todayCount' => Booking::whereDate('pickup_date', now())->count(),
            'pending'    => Booking::where('status', 'pending')->count(),
            'low'        => Phone::where('stock', '<', 5)->orderBy('stock')->get(),
            'recent'     => Booking::with(['user', 'phone'])->latest()->limit(6)->get(),
        ]);
    }
}
