<div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createEventModalLabel">Crear Evento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                    <hr class="my-4">
                    <div class="p-3 border rounded">
                        <h6>Asignar Responsable</h6>
                        <div class="mb-3">
                            <label for="responsibleEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="responsibleEmail" name="responsible_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="responsiblePassword" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="responsiblePassword" name="responsible_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="confirmPassword" name="responsible_password_confirmation" required>
                        </div>
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
