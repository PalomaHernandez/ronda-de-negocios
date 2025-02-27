<div id="endMatchingModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <button onclick="closeModal('endMatchingModal')"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>
        <h2 class="text-xl font-bold mb-4">Confirmar terminación</h2>
        <p class="mb-4">¿Estás seguro de que deseas terminar el periodo de matcheo para este evento?</p>
        <form id="endMatchingForm" method="POST">
            @csrf
            @method('PATCH') <!-- Usualmente se utiliza PATCH para este tipo de cambios -->
            <input type="hidden" id="endMatchingEventId" name="event_id">
            <div class="flex justify-end">
                <button type="button" onclick="closeModal('endMatchingModal')"
                    class="btn-gray">
                    Cancelar
                </button>
                <button type="submit" class="btn">
                    Terminar
                </button>
            </div>
        </form>
    </div>
</div>