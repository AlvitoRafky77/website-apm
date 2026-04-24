<?php

namespace App\Mail;

use App\Models\B2bRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class B2bAccountApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public B2bRegistration $registration) 
    {
        $this->registration = $registration;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat! Akun B2B Anda Telah Disetujui - PT Agro Pangan Maju',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.b2b-account-approved',
        );
    }
}
