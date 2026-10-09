<?php
namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Phone;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $r)
    {
        $phones = Phone::with(['category', 'specs', 'reviews.user'])->withAvg('reviews', 'rating')->withCount('reviews')
            ->when($r->category, fn ($q, $c) => $q->where('category_id', $c))
            ->when($r->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('brand', 'like', "%$s%")))
            ->orderBy('brand')->orderBy('name')
            ->paginate(9)->withQueryString();

        return view('home', [
            'phones' => $phones,
            'banners' => Banner::where('active', true)->orderBy('sort_order')->orderBy('id')->limit(3)->get(),
            'categories' => Category::orderBy('name')->get(),
            'saved' => auth()->check() ? Wishlist::where('user_id', auth()->id())->pluck('phone_id')->all() : [],
        ]);
    }
}
