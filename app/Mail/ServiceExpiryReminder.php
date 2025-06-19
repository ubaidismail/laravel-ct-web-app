<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomerServices;

class ServiceExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $service;
    public $user;
    public $daysLeft;
    /**
     * Create a new message instance.
     */
    public function __construct(CustomerServices $service)
    {
        $this->service = $service;
        $this->user = $service->user;
        $this->daysLeft = round(now()->diffInDays($service->end_date));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Service Expiry Reminder '. $this->service->service_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            view: 'emails.service-reminder',
            with: ([
                'userName' => $this->user->name,
                'serviceName' => $this->service->service_name,
                'endDate' => $this->service->end_date,
                'daysLeft' => $this->daysLeft,
            ]),

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
