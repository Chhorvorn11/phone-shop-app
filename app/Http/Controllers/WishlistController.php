<?php
namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $r)
    {
        $ids = Wishlist::where('user_id', $r->user()->id)->pluck('phone_id');
        return view('wishlist', ['phones' => Phone::with('category')->whereIn('id', $ids)->get()]);
    }

    public function toggle(Request $r, Phone $phone)
    {
        $row = Wishlist::where(['user_id' => $r->user()->id, 'phone_id' => $phone->id])->first();
        $row ? $row->delete() : Wishlist::create(['user_id' => $r->user()->id, 'phone_id' => $phone->id]);
        return back();
    }
}
