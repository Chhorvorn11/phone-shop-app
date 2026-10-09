<?php
namespace App\Http\Controllers;

use App\Models\CustomerProfile;
use App\Support\Picture;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit(Request $r)
    {
        return view('profile', ['p' => CustomerProfile::firstOrNew(['user_id' => $r->user()->id])]);
    }

    public function update(Request $r)
    {
        $d = $r->validate([
            'phone_number' => 'nullable|max:30', 'address' => 'nullable|max:200', 'city' => 'nullable|max:80',
            'avatar' => Picture::rules(),
        ]);
        $profile = CustomerProfile::firstOrNew(['user_id' => $r->user()->id]);
        $d['avatar'] = Picture::handle($r, 'avatar', 'avatars', $profile->avatar);
        $profile->fill($d)->save();
        return back()->with('ok', 'Profile saved.');
    }
}
