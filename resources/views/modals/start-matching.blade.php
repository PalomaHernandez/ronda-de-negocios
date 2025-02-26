<div id="startMatchingModal"
    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center p-4 hidden mt-10">

    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg relative">
        <button onclick="closeModal('startMatchingModal')"
            class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark text-xl"></i>
        </button>

        <h2 class="text-xl font-bold mb-4">Detalles del periodo de reuniones</h2>

        <!-- Formulario -->
        <form id="startMatchingForm" method="POST">
            @csrf
            @method('PATCH') <!-- Esto se usa para que Laravel lo reconozca como PATCH -->
            <input type="hidden" id="startMatchingEventId" name="event_id">

            <div class="space-y-4">
                <div>
                    <label for="startsAt">Hora de inicio:</label>
                    <input type="time" id="startsAt" name="starts_at" required class="border p-2 rounded mb-2 w-full">
                </div>

                <div>
                    <label for="endsAt">Hora de finalización:</label>
                    <input type="time" id="endsAt" name="ends_at" required class="border p-2 rounded mb-2 w-full">
                </div>

                <div>
                    <label for="meetingsPerUser">Cantidad de reuniones por participante:</label>
                    <input type="number" id="meetingsPerUser" name="meetings_per_user" required
                        class="border p-2 rounded mb-2 w-full">
                </div>

                <div>
                    <label for="meetingDuration">Duración de reuniones (en minutos):</label>
                    <input type="number" id="meetingDuration" name="meeting_duration" required
                        class="border p-2 rounded mb-2 w-full">
                </div>

                <div>
                    <label for="timeBetweenMeetings">Tiempo de descanso entre reuniones (en minutos):</label>
                    <input type="number" id="timeBetweenMeetings" name="time_between_meetings" required
                        class="border p-2 rounded mb-2 w-full">
                </div>
            </div>

            <!-- Botones alineados a la derecha -->
            <div class="flex justify-end space-x-4 mt-6">
                <button type="button" onclick="closeModal('startMatchingModal')" class="btn-gray">
                    Cancelar
                </button>
                <button type="submit" class="btn">
                    Iniciar
                </button>
            </div>
        </form>
    </div>
</div>