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

    <h2>Cronograma Individual - Evento {{ $eventId }}</h2>
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
                <td>{{ $meeting->time }}</td>
                <td>{{ $meeting->assigned_table ?? 'No asignada' }}</td>
                <td>{{ $meeting->participant_role }}</td>
                <td>{{ $meeting->other_participant_name }}</td>
                <td>{{ $meeting->reason }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No hay reuniones para este participante.</td>
            </tr>
        @endforelse
    </tbody>
</table>


</body>
</html>
