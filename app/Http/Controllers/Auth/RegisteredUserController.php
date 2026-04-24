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
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
