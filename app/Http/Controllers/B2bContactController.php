<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class B2bContactController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('b2b.contact.index', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:13'],
            'topic' => ['required', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        ContactMessage::create([
            'name' => $validated['name'],
            'company' => $validated['company'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'topic' => '[B2B] ' . $validated['topic'], // Tambah prefix B2B
            'message' => $validated['message'],
            'status' => 'new',
        ]);

        return back()->with('success', 'Pesan Anda berhasil dikirim. Account Manager kami akan segera merespons.');
    }
}
