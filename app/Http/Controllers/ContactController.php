<?php
namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create() { return view('contact'); }

    public function store(Request $r)
    {
        ContactMessage::create($r->validate([
            'name' => 'required|max:100', 'email' => 'required|email|max:150',
            'subject' => 'nullable|max:150', 'message' => 'required|max:2000',
        ]));
        return redirect()->route('home')->with('ok', 'Message sent. We will reply by email.');
    }
}
