<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tu cronograma</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; text-align: center;">

    <table align="center" width="100%" style="max-width: 600px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);">
        <tr>
            <td>
                <h2 style="color: #333;">Tu cronograma</h2>
                <p style="font-size: 16px; color: #555;">
                    Hola {{ $participant_name }}!
                </p>
                <p style="font-size: 16px; color: #555;">Adjunto vas a encontrar tu cronograma de reuniones para el evento <strong>{{ $event->title }} </strong>.</p>
                <p style="font-size: 16px; color: #555;">Si tenés alguna consulta, no dudes en contactarnos.</p>
                <p style="font-size: 16px; color: #555;">¡Te esperamos!</p>
                <p style="margin: 20px 0;">
                    <a href="https://rondas-uns.vercel.app/{{ $event->slug }}"
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
