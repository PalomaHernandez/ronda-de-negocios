<!-- resources/views/emails/event-created.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notificación de reunion</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; text-align: center;">

    <table align="center" width="100%" style="max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);">
        <tr>
            <td>
                <h2 style="color: #333;">Notificación de reunion</h2>
                <p style="font-size: 16px; color: #555;">
                    Hola! <strong>{{ $msg }}</strong>.
                </p>
                @if ($status === "Aceptada" || $status === "Rechazada")
                    <p style="font-size: 16px; color: #555;">
                        El estado de tu reunión ha sido actualizado.
                    </p>
                @endif
                <p style="margin: 20px 0;">
                    <a href="http://localhost:5174/{{ $eventName }}"
                       style="display: inline-block; background-color: #007bff; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-size: 16px;">
                        Ir a la página del evento
                    </a>
                </p>
                <p style="color: #777;">¡Gracias por usar <strong>Rondas UNS</strong>! 🚀</p>
            </td>
        </tr>
    </table>

</body>
</html>
