<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\B2bAccountApproved;
use App\Mail\B2bAccountRejected;
use App\Models\B2bRegistration;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class B2bRegistrationController extends Controller
{
    /**
     * List all B2B registrations with optional filters.
     */
    public function index(Request $request): View
    {
        $query = B2bRegistration::latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('company_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('pic_name', 'like', "%{$s}%");
            });
        }

        $registrations = $query->paginate(15)->withQueryString();

        $counts = [
            'all'      => B2bRegistration::count(),
            'pending'  => B2bRegistration::where('status', 'pending')->count(),
            'approved' => B2bRegistration::where('status', 'approved')->count(),
            'rejected' => B2bRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.b2b-registrations.index', compact('registrations', 'counts'));
    }

    /**
     * Show detail of a single B2B registration.
     */
    public function show(B2bRegistration $b2bRegistration): View
    {
        return view('admin.b2b-registrations.show', compact('b2bRegistration'));
    }

    /**
     * Approve & create a user account.
     */
    public function approve(Request $request, B2bRegistration $b2bRegistration): RedirectResponse
    {
        if (!$b2bRegistration->isPending()) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        // Create user account
        $user = User::create([
            'name'               => $b2bRegistration->pic_name,
            'email'              => $b2bRegistration->email,
            'phone'              => $b2bRegistration->phone,
            'password'           => $b2bRegistration->password, // already hashed
            'user_type'          => 'b2b',
            'is_active'          => true,
            'b2b_registration_id' => $b2bRegistration->id,
        ]);

        $user->assignRole('b2b_customer');

        // Update registration status
        $b2bRegistration->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'user_id'     => $user->id,
        ]);

        // Send approval email
        try {
            Mail::to($b2bRegistration->email)->send(new B2bAccountApproved($b2bRegistration));
        } catch (\Exception $e) {
            // Log but don't fail
            Log::warning('Failed to send B2B approval email: ' . $e->getMessage());
        }

        return back()->with('success', "Akun B2B untuk {$b2bRegistration->company_name} berhasil disetujui dan email notifikasi telah dikirim.");
    }

    /**
     * Reject a B2B registration.
     */
    public function reject(Request $request, B2bRegistration $b2bRegistration): RedirectResponse
    {
        $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ], [
            'admin_notes.required' => 'Harap berikan alasan penolakan.',
        ]);

        if (!$b2bRegistration->isPending()) {
            return back()->with('error', 'Pendaftaran ini sudah diproses sebelumnya.');
        }

        $b2bRegistration->update([
            'status'      => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // Send rejection email
        try {
            Mail::to($b2bRegistration->email)->send(new B2bAccountRejected($b2bRegistration));
        } catch (\Exception $e) {
            Log::warning('Failed to send B2B rejection email: ' . $e->getMessage());
        }

        return back()->with('success', "Pendaftaran {$b2bRegistration->company_name} telah ditolak dan email notifikasi telah dikirim.");
    }

    /**
     * Download MOU (Surat Perjanjian) as PDF.
     */
    public function downloadMou(B2bRegistration $b2bRegistration)
    {
        $pdf = Pdf::loadView('admin.b2b-registrations.mou', [
            'registration' => $b2bRegistration,
            'date'         => now()->translatedFormat('d F Y'),
        ]);

        $pdf->setPaper('A4', 'portrait');
        $filename = 'MOU-APM-' . str_replace(' ', '-', strtoupper($b2bRegistration->company_name)) . '.pdf';

        return $pdf->download($filename);
    }
}
