<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;

class IndividualScheduleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;
    public $participant_name;
    public $event_name;

    /**
     * Create a new message instance.
     */
    public function __construct($schedule, $participant_name, $event_name)
    {
        $this->schedule = $schedule;
        $this->participant_name = $participant_name;
        $this->event_name = $event_name;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Tu cronograma de reuniones',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.individual_schedule',
            with: [
                'participant_name' => $this->participant_name,
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
            Attachment::fromData(fn () => $this->schedule, 'cronograma.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
