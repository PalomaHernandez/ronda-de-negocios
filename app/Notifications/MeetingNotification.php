<?php
/*
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MeetingNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    // 📌 Notificación en la base de datos
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message ?? 'Notificación sin mensaje',
            'timestamp' => now(),
        ];
    }

    // 📌 Opcional: Notificación por correo
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Nueva Notificación')
            ->line($this->message)
            ->action('Ver Notificación', url('/notificaciones'))
            ->line('Gracias por usar nuestra aplicación.');
    }

    public function via($notifiable)
    {
        return ['database']; // Opcional: ['mail', 'database']
    }
    
}
    */
