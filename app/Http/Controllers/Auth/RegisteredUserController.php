<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\B2bRegistration;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show the registration type selection page.
     */
    public function create(): View
    {
        return view('auth.register-choose');
    }

    /**
     * Show the B2C registration form.
     */
    public function createB2c(): View
    {
        return view('auth.register');
    }

    /**
     * Handle B2C registration (direct activation).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone'    => ['nullable', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:13'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'      => 'Nama lengkap wajib diisi.',
            'email.required'     => 'Alamat email wajib diisi.',
            'email.email'        => 'Alamat email harus menggunakan format yang valid dan mengandung \'@\' (contoh: nama@domain.com).',
            'email.unique'       => 'Alamat email ini sudah terdaftar.',
            'phone.regex'        => 'Nomor HP hanya boleh berisi angka.',
            'phone.min'          => 'Nomor HP minimal 10 digit.',
            'phone.max'          => 'Nomor HP maksimal 13 digit.',
            'password.required'  => 'Kata sandi wajib diisi.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'user_type' => 'b2c',
            'is_active' => true,
        ]);

        $user->assignRole('b2c_customer');
        event(new Registered($user));
        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
