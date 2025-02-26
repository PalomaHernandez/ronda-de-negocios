@extends('layout.app')

@section('title')
    Eventos
@endsection

@section('content')
    <main class="mt-28 w-full p-4">
        <!-- Mensajes de sesión -->
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong class="font-bold">Hubo algunos errores:</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        <!-- Tabla de eventos -->
        <div class="bg-white shadow-md my-6 w-full">
            <table class="table-auto border-gray-300 overflow-y-auto w-full">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm font-mono">
                        <th class="py-3 px-6 text-center">Nombre del Evento</th>
                        <th class="py-3 px-6 text-center">Fecha</th>
                        <th class="py-3 px-6 text-center">Estado</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 bg-white text-sm font-light">
                    @foreach ($events as $event)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-center relative">{{ $event->title }}</td>
                            <td class="py-3 px-6 text-center relative">
                                {{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}
                            </td>
                            <td class="py-3 px-6 text-center relative">
                                <span class="py-1 px-3 rounded-full text-xs
                                                        @if ($event->status->value === 'Inscripcion') bg-sky-600 text-white
                                                        @elseif ($event->status->value === 'Matcheo') bg-amber-600 text-white
                                                        @elseif ($event->status->value === 'Terminado') bg-emerald-600 text-white
                                                        @endif">
                                    {{ $event->status ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center relative" x-data="{ open: false }">
                                <!-- Botón para abrir el menú -->
                                <button @click="open = !open" @click.away="open = false"
                                    class="text-gray-600 hover:text-gray-800">
                                    <i class="fa-solid fa-ellipsis-vertical text-xl"></i>
                                </button>

                                <!-- Menú desplegable -->
                                <div x-show="open" x-transition
                                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md z-20">
                                    <ul class="py-1 text-gray-700">
                                        <li>
                                            <template x-if="'{{ $event->status }}' === 'Terminado'">
                                                <button onclick="window.location.href='/cronograma/{{ $event->id }}'"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center">
                                                    <i class="fa-solid fa-calendar mr-2"></i> Ver cronograma
                                                </button>
                                            </template>
                                        </li>
                                        <li>
                                            <template x-if="'{{ $event->status }}' === 'Inscripcion'">
                                                <button onclick="startMatching(
                                                                    {{ $event->id }},
                                                                    '{{ $event->starts_at ?? 'null' }}',
                                                                    '{{ $event->ends_at ?? 'null' }}',
                                                                    '{{ $event->meeting_duration ?? 'null' }}',
                                                                    '{{ $event->time_between_meetings ?? 'null' }}')
                                                                    '{{ $event->meetings_per_user ?? 'null' }}'"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center">
                                                    <i class="fa-solid fa-play mr-2"></i> Iniciar Matcheo
                                                </button>
                                            </template>
                                        </li>
                                        <li>
                                            <template x-if="'{{ $event->status }}' === 'Matcheo'">
                                                <button onclick="confirmEndMatching({{ $event->id }})"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 flex items-center">
                                                    <i class="fa-solid fa-stop mr-2"></i> Terminar Matcheo
                                                </button>
                                            </template>
                                        </li>
                                        <li>
                                            <button onclick="confirmDelete({{ $event->id }})"
                                                class="w-full text-left px-4 py-2 hover:bg-red-100 text-red-500 flex items-center">
                                                <i class="fa-solid fa-trash mr-2"></i> Eliminar evento
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>


            </table>
        </div>

        <!-- Botón Crear Evento -->
        <div class="flex justify-end mt-6">
            <button class="btn-green" onclick="openModal('createEventModal')">
                Crear Evento
            </button>
        </div>

        <!-- Modales -->
        <div id="modals">
            <!-- Modal Crear Evento -->
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
                                    <input type="password" id="confirmPassword" name="responsible_password_confirmation"
                                        required class="w-full p-2 border rounded" />
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


            <!-- Modal Confirmar Eliminación -->
            <div id="deleteEventModal"
                class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
                <div class="bg-white p-6 rounded shadow-lg w-1/3 relative">
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
                            <input type="time" id="startsAt" name="starts_at" required
                                class="border p-2 rounded mb-2 w-full">
                        </div>

                        <div>
                            <label for="endsAt">Hora de finalización:</label>
                            <input type="time" id="endsAt" name="ends_at" required class="border p-2 rounded mb-2 w-full">
                        </div>

                        <div>
                            <label for="meetingsPerUser">Número de reuniones por usuario:</label>
                            <input type="number" id="meetingsPerUser" name="meetings_per_user" required
                                class="border p-2 rounded mb-2 w-full">
                        </div>

                        <div>
                            <label for="meetingDuration">Duración de reuniones (minutos):</label>
                            <input type="number" id="meetingDuration" name="meeting_duration" required
                                class="border p-2 rounded mb-2 w-full">
                        </div>

                        <div>
                            <label for="timeBetweenMeetings">Tiempo de descanso entre reuniones (minutos):</label>
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

        <!-- Modal Confirmar Terminar Periodo de Matcheo -->
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
                            class="bg-gray-500 text-white px-4 py-2 rounded mr-2 hover:bg-gray-600">
                            Cancelar
                        </button>
                        <button type="submit" class="btn">
                            Terminar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </main>
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = "hidden"; // Evita el scroll del body
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = "auto";
        }

        function confirmDelete(eventId) {
            const form = document.getElementById('deleteEventForm');
            form.action = `/events/delete/${eventId}`;
            openModal('deleteEventModal');
        }

        function startMatching(eventId, startsAt, endsAt, meetingDuration, timeBetweenMeetings) {
            // Asignar la acción del formulario
            document.getElementById('startMatchingForm').action = `/events/start-matching/${eventId}`;

            // Llenar los valores existentes en los inputs (si los hay)
            document.getElementById('startsAt').value = startsAt !== 'null' && startsAt ? startsAt : '';
            document.getElementById('endsAt').value = endsAt !== 'null' && endsAt ? endsAt : '';
            document.getElementById('meetingDuration').value = meetingDuration !== 'null' && meetingDuration ? meetingDuration : '';
            document.getElementById('timeBetweenMeetings').value = timeBetweenMeetings !== 'null' && timeBetweenMeetings ? timeBetweenMeetings : '';
            document.getElementById('meetingsPerUser').value = meetingsPerUser !== 'null' && meetingsPerUser ? meetingsPerUser : '';

            openModal('startMatchingModal');
        }

        function confirmEndMatching(eventId) {
            const form = document.getElementById('endMatchingForm');
            form.action = `/events/end-matching/${eventId}`;
            openModal('endMatchingModal');
        }


        document.addEventListener("DOMContentLoaded", function () {
            const startMatchingForm = document.getElementById("startMatchingForm");

            startMatchingForm.addEventListener("submit", function (event) {
                // Obtener los inputs
                let startsAtInput = document.getElementById("startsAt");
                let endsAtInput = document.getElementById("endsAt");

                // Función para formatear la hora correctamente
                function formatTime(value) {
                    if (value && value.length === 5) { // Si el formato es HH:MM
                        return value + ":00";
                    }
                    return value; // Si ya tiene segundos, lo deja igual
                }

                // Modificar los valores antes de enviar
                startsAtInput.value = formatTime(startsAtInput.value);
                endsAtInput.value = formatTime(endsAtInput.value);
            });
        });

    </script>
@endsection