<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rondas Uns en Zul</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        h1 {
            text-align: center;
            font-size: 3rem;
        }

        table {
            margin-top: 20px;
        }

        .modal-content {
            padding: 20px;
        }

        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Rondas Uns en Zul</h1>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla de eventos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre del Evento</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->date }}</td>
                        <td>
                            <span class="badge bg-primary">
                                {{ $event->status ?? 'N/A' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Botones -->
        <div class="d-flex justify-content-end">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createEventModal">Crear Evento</button>
            <button class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteEventModal">Eliminar Evento</button>
        </div>

        <!-- Modal de Crear Evento -->
        <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Crear Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Mensaje de error si ya existe un evento con ese nombre -->
                        <div id="error-message" class="error-message" style="display: none;">
                            Ya existe un evento con ese título.
                        </div>

                        <form id="createEventForm" action="{{ route('events.create') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="eventTitle" class="form-label">Título del Evento</label>
                                <input type="text" class="form-control" id="eventTitle" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="eventDate" class="form-label">Fecha</label>
                                <input type="date" class="form-control" id="eventDate" name="date" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success" id="createEventButton">Crear</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Eliminar Evento -->
        <div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteEventModalLabel">Eliminar Evento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="deleteEventForm" action="{{ route('events.delete') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="eventTitleToDelete" class="form-label">Ingrese el título del evento</label>
                                <input type="text" class="form-control" id="eventTitleToDelete" name="delete_title" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger" id="deleteEventButton">Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script>
        // Mostrar error en el modal de crear evento
        document.getElementById('createEventButton').addEventListener('click', function() {
            var title = document.getElementById('eventTitle').value;
            var date = document.getElementById('eventDate').value;
            var errorMessage = document.getElementById('error-message');

            // Si ya existe un evento con el mismo título, mostrar el mensaje de error
            fetch(`/events/create`, {
                method: 'GET',
                body: new FormData(document.getElementById('createEventForm')),
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Mostrar el mensaje de error
                    errorMessage.style.display = 'block';
                } else {
                    // Si no hay error, enviar el formulario
                    document.getElementById('createEventForm').submit();
                }
            });
        });

        // Eliminar evento - también se envía el formulario con POST
        document.getElementById('deleteEventButton').addEventListener('click', function() {
            var titleToDelete = document.getElementById('eventTitleToDelete').value;

            if (titleToDelete) {
                // Enviar el formulario
                document.getElementById('deleteEventForm').submit();
            }
        });
    </script> -->
</body>
</html>
