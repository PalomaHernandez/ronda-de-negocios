<div id="createEventModal"
    class="fixed inset-0 left-0 w-full h-full flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-4xl relative">
        <!-- Botón de cierre -->
        <button onclick="closeModal('createEventModal')"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h2 class="text-xl font-bold mb-4">Crear Evento</h2>

        <form action="{{ route('events.create') }}" method="POST">
            @csrf

            <!-- Sección Título y Fecha (Dos columnas) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="labeled-input">
                    <label for="title" class="label">Título</label>
                    <input type="text" id="title" name="title" required class="w-full p-2 border rounded" />
                </div>

                <div class="labeled-input">
                    <label for="date" class="label">Fecha</label>
                    <input type="date" id="date" name="date" required class="w-full p-2 border rounded" />
                </div>
                <div class="labeled-input">
                    <label for="maxParticipants" class="label">Máxima cantidad de participantes</label>
                    <input type="number" id="maxParticipants" name="max_participants" min="1" required
                        class="w-full p-2 border rounded" />
                </div>
            </div>

            <!-- Asignar Responsable -->
            <div class="p-3 border rounded-lg mt-6">
                <h3 class="font-semibold text-gray-700 mb-2">Asignar Responsable</h3>

                <!-- Sección de Responsable en dos columnas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="labeled-input">
                        <label for="responsibleEmail" class="label">Email</label>
                        <input type="email" id="responsibleEmail" name="responsible_email" required
                            class="w-full p-2 border rounded" />
                    </div>

                    <div class="labeled-input">
                        <label for="responsiblePassword" class="label">Contraseña</label>
                        <input type="password" id="responsiblePassword" name="responsible_password" required
                            class="w-full p-2 border rounded" />
                    </div>

                    <div class="labeled-input">
                        <label for="confirmPassword" class="label">Confirmar Contraseña</label>
                        <input type="password" id="confirmPassword" name="responsible_password_confirmation" required
                            class="w-full p-2 border rounded" />
                    </div>
                </div>
            </div>

            <!-- Botones alineados a la derecha -->
            <div class="flex justify-end mt-6 space-x-4">
                <button type="button" onclick="closeModal('createEventModal')" class="btn-gray">
                    Cancelar
                </button>
                <button type="submit" class="btn">
                    Crear
                </button>
            </div>
        </form>
    </div>
</div>