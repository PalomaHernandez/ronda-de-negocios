<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rondas UNS Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans leading-normal tracking-normal">
    <!-- Header -->
    <header class="bg-white shadow-md fixed top-0 left-0 w-full z-10">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-xl font-bold text-gray-800">Rondas UNS Admin</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </header>

    <!-- Contenido Principal -->
    <main class="container mx-auto mt-28 px-4">
        <!-- Mensajes de sesión -->
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tabla de eventos -->
        <div class="bg-white shadow-md rounded my-6">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Nombre del Evento</th>
                        <th class="py-3 px-6 text-left">Fecha</th>
                        <th class="py-3 px-6 text-left">Estado</th>
                        <th class="py-3 px-6 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($events as $event)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $event->title }}</td>
                            <td class="py-3 px-6">{{ \Carbon\Carbon::parse($event->date)->format('d-m-Y') }}</td>
                            <td class="py-3 px-6">
                                <span class="bg-blue-500 text-white py-1 px-3 rounded-full text-xs">
                                    {{ $event->status ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <button class="text-red-500 hover:text-red-700" onclick="confirmDelete({{ $event->id }})">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Botón Crear Evento -->
        <div class="flex justify-end mt-6">
            <button class="btn-create" onclick="openModal('createEventModal')">
                Crear Evento
            </button>
        </div>

        <!-- Modales -->
        <div id="modals">
            <!-- Modal Crear Evento -->
            <div id="createEventModal"
                class="absolute top-16 left-0 w-full h-full flex items-center justify-center bg-gray-800 bg-opacity-50 hidden">
                <div class="bg-white p-6 rounded shadow-lg w-1/3 relative mt-10 mb-10">
                    <button onclick="closeModal('createEventModal')"
                        class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>

                    <h2 class="text-xl font-bold mb-4">Crear Evento</h2>
                    <form action="{{ route('events.create') }}" method="POST">
                        @csrf
                        <div class="labeled-input">
                            <label for="title" class="label">Título</label>
                            <input type="text" id="title"
                                name="title" required />
                        </div>

                        <div class="labeled-input">
                            <label for="date" class="label">Fecha</label>
                            <input type="date" id="date"
                                name="date" required />
                        </div>
                        <!-- Asignar Responsable -->
                        <hr class="my-4">
                        <div class="p-3 border rounded">
                            <h3 class="font-semibold text-gray-700 mb-2">Asignar Responsable</h3>
                            <!-- Email del Responsable -->
                            <div class="labeled-input">
                                <label for="responsibleEmail"
                                    class="label">Email</label>
                                <input type="email"
                                    id="responsibleEmail" name="responsible_email" required />
                            </div>
                            <!-- Contraseña del Responsable -->
                            <div class="labeled-input">
                                <label for="responsiblePassword"
                                    class="label">Contraseña</label>
                                <input type="password"
                                    
                                    id="responsiblePassword" name="responsible_password" required />
                            </div>
                            <!-- Confirmar Contraseña -->
                            <div class="labeled-input">
                                <label for="confirmPassword" class="label">Confirmar
                                    Contraseña</label>
                                <input type="password"
                                    id="confirmPassword" name="responsible_password_confirmation" required />
                            </div>
                        </div>

                        <div class="flex justify-end mt-3">
                            <button type="button" onclick="closeModal('createEventModal')"
                                class="btn mr-2">
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
                            <button type="button" onclick="closeModal('deleteEventModal')"
                                class="bg-gray-500 text-white px-4 py-2 rounded mr-2">
                                Cancelar
                            </button>
                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">
                                Eliminar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function confirmDelete(eventId) {
            const form = document.getElementById('deleteEventForm');
            form.action = `/events/delete/${eventId}`;
            openModal('deleteEventModal');
        }

    </script>
</body>

</html>