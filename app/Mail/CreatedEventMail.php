<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CreatedEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public $eventName;
    public $eventUrl;
    public $responsibleEmail;
    public $responsiblePassword;

    public function __construct($eventName, $eventUrl, $responsibleEmail, $responsiblePassword)
    {
        $this->eventName = $eventName;
        $this->eventUrl = $eventUrl;
        $this->responsibleEmail = $responsibleEmail;
        $this->responsiblePassword = $responsiblePassword;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Creacion de evento en Rondas uns',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.event-created',
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
