<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class GeneralScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    public $event_name;

    /**
     * Create a new message instance.
     */
    public function __construct($schedule, $event_name)
    {
        $this->schedule = $schedule;
        $this->event_name = $event_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cronograma general del evento',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.general_schedule',
            with: [
                'event_name' => $this->event_name,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->schedule, 'cronograma_general.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
