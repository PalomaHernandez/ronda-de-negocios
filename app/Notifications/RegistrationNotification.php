<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class RegistrationNotification extends Notification
{
    use Queueable;

    private $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    // 📌 Indicar que la notificación se enviará por base de datos y WebSockets
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // 📌 Guardar la notificación en la base de datos
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->message,
            'registration_id' => $notifiable->id, // Relacionar con la Registration
        ];
    }

    // 📌 Enviar la notificación en tiempo real (WebSockets)
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => $this->message,
            'registration_id' => $notifiable->id,
        ]);
    }
}
