<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\B2bRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class B2bRegisterController extends Controller
{
    /**
     * Show the B2B registration form.
     */
    public function create(): View
    {
        return view('auth.register-b2b');
    }

    /**
     * Handle B2B registration submission (status: pending, no auto-login).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'company_name'         => ['required', 'string', 'max:255'],
            'company_type'         => ['required', 'in:PT,CV,UD,Firma,Koperasi,Perorangan,Lainnya'],
            'npwp'                 => ['nullable', 'string', 'max:30'],
            'company_address'      => ['required', 'string'],
            'company_city'         => ['nullable', 'string', 'max:100'],
            'company_province'     => ['nullable', 'string', 'max:100'],
            'company_postal_code'  => ['nullable', 'string', 'max:10'],
            'pic_name'             => ['required', 'string', 'max:255'],
            'pic_position'         => ['nullable', 'string', 'max:100'],
            'email'                => ['required', 'email', 'max:255', 'unique:b2b_registrations,email'],
            'phone'                => ['required', 'string', 'regex:/^[0-9]+$/', 'min:10', 'max:13'],
            'password'             => ['required', 'confirmed', Rules\Password::defaults()],
            'company_profile'      => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'siup'                 => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'akta'                 => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'agree_terms'          => ['required', 'accepted'],
        ], [
            'company_name.required'    => 'Nama perusahaan wajib diisi.',
            'company_type.required'    => 'Jenis perusahaan wajib dipilih.',
            'company_address.required' => 'Alamat perusahaan wajib diisi.',
            'pic_name.required'        => 'Nama PIC wajib diisi.',
            'email.required'           => 'Alamat email wajib diisi.',
            'email.unique'             => 'Email ini sudah terdaftar.',
            'phone.required'           => 'Nomor telepon wajib diisi.',
            'phone.regex'              => 'Nomor telepon hanya boleh berisi angka.',
            'phone.min'                => 'Nomor telepon minimal 10 digit.',
            'phone.max'                => 'Nomor telepon maksimal 13 digit.',
            'password.required'        => 'Kata sandi wajib diisi.',
            'agree_terms.accepted'     => 'Anda harus menyetujui syarat & ketentuan.',
            'company_profile.max'      => 'Ukuran file company profile maksimal 5MB.',
        ]);

        // Handle file uploads
        $companyProfilePath = null;
        $siupPath = null;
        $aktaPath = null;

        if ($request->hasFile('company_profile')) {
            $companyProfilePath = $request->file('company_profile')
                ->store('b2b-documents/company-profiles', 'public');
        }
        if ($request->hasFile('siup')) {
            $siupPath = $request->file('siup')
                ->store('b2b-documents/siup', 'public');
        }
        if ($request->hasFile('akta')) {
            $aktaPath = $request->file('akta')
                ->store('b2b-documents/akta', 'public');
        }

        B2bRegistration::create([
            'company_name'        => $request->company_name,
            'company_type'        => $request->company_type,
            'npwp'                => $request->npwp,
            'company_address'     => $request->company_address,
            'company_city'        => $request->company_city,
            'company_province'    => $request->company_province,
            'company_postal_code' => $request->company_postal_code,
            'pic_name'            => $request->pic_name,
            'pic_position'        => $request->pic_position,
            'email'               => $request->email,
            'phone'               => $request->phone,
            'password'            => Hash::make($request->password),
            'company_profile_path' => $companyProfilePath,
            'siup_path'           => $siupPath,
            'akta_path'           => $aktaPath,
            'status'              => 'pending',
        ]);

        return redirect()->route('register.b2b.pending');
    }

    /**
     * Show the pending approval page.
     */
    public function pending(): View
    {
        return view('auth.register-pending');
    }
}
