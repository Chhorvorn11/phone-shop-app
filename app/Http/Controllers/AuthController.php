<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $r)
    {
        $cred = $r->validate(['email' => 'required|email', 'password' => 'required']);

        if (! Auth::attempt($cred, $r->boolean('remember'))) {
            return back()->withErrors(['email' => 'Wrong email or password.'])->onlyInput('email');
        }
        $r->session()->regenerate();
        return redirect()->intended(route('dashboard'));
    }

    public function register(Request $r)
    {
        $d = $r->validate([
            'name'     => 'required|max:100',
            'email'    => 'required|email|max:150|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        // New accounts are always normal users (the role column defaults to "user").
        $user = User::create(['name' => $d['name'], 'email' => $d['email'], 'password' => Hash::make($d['password'])]);
        Auth::login($user);
        $r->session()->regenerate();
        return redirect()->route('dashboard');
    }

    public function logout(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('home');
    }
}
