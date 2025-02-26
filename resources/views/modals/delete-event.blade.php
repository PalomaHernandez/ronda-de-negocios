<div id="deleteEventModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <button onclick="closeModal('deleteEventModal')"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h2 class="text-xl font-bold mb-4">Confirmar eliminación</h2>
        <p class="mb-4">¿Estás seguro de que deseas eliminar este evento?</p>
        <form id="deleteEventForm" method="POST">
            @csrf
            @method('DELETE')
            <input type="hidden" id="deleteEventId" name="event_id">
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('deleteEventModal')" class="btn-gray">
                    Cancelar
                </button>
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                    Eliminar
                </button>
            </div>
        </form>
    </div>
</div>
</div>