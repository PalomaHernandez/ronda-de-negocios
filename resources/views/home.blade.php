@extends('layout.app')

@section('title')
    Eventos
@endsection

@section('content')
    <main class="mt-28 w-full p-4">
        @if ($errors->any())
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="alert alert-error">
                <strong>Hubo algunos errores:</strong>
                <ul class="mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button @click="show = false" class="absolute top-2 right-2 text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                class="alert alert-success">
                {{ session('success') }}
                <button @click="show = false" class="absolute top-2 right-2 text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" class="alert alert-error">
                {{ session('error') }}
                <button @click="show = false" class="absolute top-2 right-2 text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        @endif



        <!-- Tabla de eventos -->
        <div class="bg-white shadow-md my-6 w-full rounded-lg overflow-hidden overflow-x-auto">
            <table class="table-auto border-gray-300 w-full rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-base font-sans rounded-t-lg">
                        <th class="py-3 px-6 text-center">Nombre del Evento</th>
                        <th class="py-3 px-6 text-center">Fecha</th>
                        <th class="py-3 px-6 text-center">Estado</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 bg-white text-sm font-light rounded-b-lg">
                    @foreach ($events as $event)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 ">
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
                                    class="fixed right-4 mt-2 w-48 bg-white border border-gray-200 shadow-lg rounded-md z-50">
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
                                                <button
                                                    onclick="startMatching(
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

        @include('modals.create-event')
        @include('modals.delete-event')
        @include('modals.start-matching')
        @include('modals.end-matching')

    </main>
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.style.overflow = "hidden";
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