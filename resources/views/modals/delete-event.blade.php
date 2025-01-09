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
