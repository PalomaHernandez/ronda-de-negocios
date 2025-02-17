<!-- resources/views/emails/event-created.blade.php -->
<p>Hola,</p>

<p>Se ha creado el evento <strong>{{ $eventName }}</strong></p>

<p>Estos son tus datos de acceso:</p>
<ul>
    <li><strong>Pagina del evento:</strong> http://localhost:5174/{{ $eventUrl }}</li>
    <li><strong>Usuario:</strong> {{ $responsibleEmail }}</li>
    <li><strong>Contraseña:</strong> {{ $responsiblePassword }}</li>
</ul>

<p>¡Gracias por usar Rondas UNS!</p>
