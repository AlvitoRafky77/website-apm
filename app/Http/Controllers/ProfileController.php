<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\B2bRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user    = $request->user();
        $b2bData = null;

        if ($user->isB2b() && $user->b2bRegistration) {
            $b2bData = $user->b2bRegistration;
        }

        return view('profile.edit', compact('user', 'b2bData'));
    }

    /**
     * Update the user's profile information (B2C — name, email, phone, address).
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update B2B company profile data.
     */
    public function updateB2b(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if (!$user->isB2b() || !$user->b2bRegistration) {
            abort(403);
        }

        $validated = $request->validate([
            'pic_name'             => 'required|string|max:255',
            'pic_position'         => 'nullable|string|max:100',
            'phone'                => 'required|string|max:20',
            'company_name'         => 'required|string|max:255',
            'company_type'         => 'nullable|string|max:100',
            'npwp'                 => 'nullable|string|max:30',
            'company_address'      => 'nullable|string',
            'company_city'         => 'nullable|string|max:100',
            'company_province'     => 'nullable|string|max:100',
            'company_postal_code'  => 'nullable|string|max:10',
        ]);

        // Update user phone
        $user->update(['phone' => $validated['phone']]);

        // Update B2B registration profile
        $user->b2bRegistration->update([
            'pic_name'            => $validated['pic_name'],
            'pic_position'        => $validated['pic_position'],
            'phone'               => $validated['phone'],
            'company_name'        => $validated['company_name'],
            'company_type'        => $validated['company_type'],
            'npwp'                => $validated['npwp'],
            'company_address'     => $validated['company_address'],
            'company_city'        => $validated['company_city'],
            'company_province'    => $validated['company_province'],
            'company_postal_code' => $validated['company_postal_code'],
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
