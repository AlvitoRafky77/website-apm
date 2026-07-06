<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class B2bContactController extends Controller
{
    private function ensureB2bAccess(): void
    {
        abort_unless(
            auth()->check()
                && auth()->user()->isB2b()
                && auth()->user()->b2bRegistration,
            403,
            'Akses ditolak. Halaman ini hanya untuk Mitra B2B.'
        );
    }

    public function index()
    {
        $this->ensureB2bAccess();

        $user = auth()->user();
        return view('b2b.contact.index', compact('user'));
    }

    public function store(Request $request)
    {
        $this->ensureB2bAccess();
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
