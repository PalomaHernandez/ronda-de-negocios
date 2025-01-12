<!-- Modal Crear Evento -->
<div id="createEventModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-1/3 relative">
        <!-- Botón de cierre en la esquina superior derecha -->
        <button
            onclick="closeModal('createEventModal')"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700"
        >
            ✖
        </button>

        <h2 class="text-xl font-bold mb-4">Crear Evento</h2>
        <form action="{{ route('events.create') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    required
                    class="w-full border-gray-300 rounded mt-1"
                />
            </div>
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Fecha</label>
                <input
                    type="date"
                    id="date"
                    name="date"
                    required
                    class="w-full border-gray-300 rounded mt-1"
                />
            </div>
            <div class="flex justify-end">
                <!-- Botón Cancelar -->
                <button
                    type="button"
                    onclick="closeModal('createEventModal')"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2"
                >
                    Cancelar
                </button>
                <!-- Botón Crear -->
                <button
                    type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Crear
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Función para abrir el modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    // Función para cerrar el modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
