<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        h2 {
            color: #2c3e50;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2>Cronograma de Reuniones - Evento {{ $eventId }}</h2>

    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Mesa</th>
                <th>Solicitante</th>
                <th>Receptor</th>
            </tr>
        </thead>
        <tbody>
            @php
                $reunionesPorHora = $meetings->groupBy('formatted_time');
            @endphp

            @foreach($reunionesPorHora as $hora => $reuniones)
                @php
                    $totalReuniones = count($reuniones);
                @endphp

                @foreach($reuniones as $index => $meeting)
                    <tr>

                        @if ($index === 0)
                            <td rowspan="{{ $totalReuniones }}">{{ $hora }}</td>
                        @endif
                        <td>{{ $meeting->assigned_table ?? 'No asignada' }}</td>
                        <td>{{ $meeting->requester_name }}</td>
                        <td>{{ $meeting->receiver_name }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

</body>
</html>
