<?php

namespace App\Mail;

use App\Models\Donation;
use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceipt extends Mailable
{
    use Queueable, SerializesModels;

    public Donation $donation;
    public Profile $profile;

    public function __construct(Donation $donation, Profile $profile)
    {
        $this->donation = $donation;
        $this->profile = $profile;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for your donation – ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.donation-receipt',
        );
    }

    /** @return array<int, \Illuminate\Mail\Mailables\Attachment> */
    public function attachments(): array
    {
        return [];
    }
}
