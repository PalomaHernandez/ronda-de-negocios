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

    <h2>Cronograma - {{ $event->title }}</h2>
    <h3>Dia: {{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}</h3>
    <h3>Horario: {{ \Carbon\Carbon::parse($event->starts_at)->format('H:i') }} a {{ \Carbon\Carbon::parse($event->ends_at)->format('H:i') }}</h3>
    <h3>Participante: {{ $userName }}</h3>


<table>
    <thead>
        <tr>
            <th>Hora</th>
            <th>Mesa</th>
            <th>Rol</th>
            <th>Otro Participante</th>
            <th>Motivo</th>
        </tr>
    </thead>
    <tbody>
        @forelse($meetings as $meeting)
            <tr>
                <td>{{ $meeting->formatted_time }}</td>
                <td>{{ $meeting->assigned_table ?? 'No asignada' }}</td>
                <td>{{ $meeting->participant_role }}</td>
                <td>{{ $meeting->other_participant_name }}</td>
                <td>{{ $meeting->reason }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay reuniones para este participante.</td>
            </tr>
        @endforelse
    </tbody>
</table>


</body>
</html>
