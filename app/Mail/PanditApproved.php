<?php

namespace App\Mail;

use App\Models\PanditProfile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PanditApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PanditProfile $pandit)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Congratulations! Your Pandit Account is Approved - '.config('app.name', 'Ved Mitra'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pandit-approved',
        );
    }
}
