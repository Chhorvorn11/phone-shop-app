<?php
namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /** One review per user per phone. Posting again edits it. */
    public function store(Request $r, Phone $phone)
    {
        $d = $r->validate(['rating' => 'required|integer|between:1,5', 'comment' => 'nullable|string|max:500']);
        Review::updateOrCreate(['user_id' => $r->user()->id, 'phone_id' => $phone->id], $d);
        return back()->with('ok', 'Thanks for your review.');
    }
}
