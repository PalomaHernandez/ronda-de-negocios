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
                <button type="submit" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 transition">
                    Cerrar sesion
                </button>
            </form>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto mt-24 px-4">
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
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach ($events as $event)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6">{{ $event->title }}</td>
                            <td class="py-3 px-6">{{ $event->date }}</td>
                            <td class="py-3 px-6">
                                <span class="bg-blue-500 text-white py-1 px-3 rounded-full text-xs">
                                    {{ $event->status ?? 'N/A' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Botones para abrir el modal -->
        <div class="flex justify-end mt-6">
            <!-- Botón Crear Evento -->
            <button class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition"
                onclick="openModal('createEventModal')">
                Crear Evento
            </button>
            <!-- Botón Eliminar Evento -->
            <button class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 transition ml-4"
                onclick="openModal('deleteEventModal')">
                Eliminar Evento
            </button>
        </div>
    </div>

   <!-- Modal Crear Evento -->
<div id="createEventModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-1/3 relative">
        <!-- Botón de cierre en la esquina superior derecha -->
        <button onclick="closeModal('createEventModal')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            ✖
        </button>

        <h2 class="text-xl font-bold mb-4">Crear Evento</h2>
        <form action="{{ route('events.create') }}" method="POST">
            @csrf
            <!-- Título del Evento -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Título</label>
                <input type="text" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 px-2" id="title" name="title" required />
            </div>
            <!-- Fecha del Evento -->
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Fecha</label>
                <input type="date" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 px-2" id="date" name="date" required />
            </div>

            <!-- Asignar Responsable -->
            <hr class="my-4">
            <div class="p-3 border rounded">
                <h3 class="font-semibold text-gray-700 mb-2">Asignar Responsable</h3>
                <!-- Email del Responsable -->
                <div class="mb-4">
                    <label for="responsibleEmail" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 px-2" id="responsibleEmail" name="responsible_email" required />
                </div>
                <!-- Contraseña del Responsable -->
                <div class="mb-4">
                    <label for="responsiblePassword" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 px-2" id="responsiblePassword" name="responsible_password" required />
                </div>
                <!-- Confirmar Contraseña -->
                <div class="mb-4">
                    <label for="confirmPassword" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input type="password" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 px-2" id="confirmPassword" name="responsible_password_confirmation" required />
                </div>
            </div>

            <div class="flex justify-end mt-3">
                <!-- Botón Cancelar -->
                <button type="button" onclick="closeModal('createEventModal')" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">
                    Cancelar
                </button>
                <!-- Botón Crear -->
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Crear
                </button>
            </div>
        </form>
    </div>
</div>

    <!-- Modal Eliminar Evento -->
    <div id="deleteEventModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg w-1/3 relative">
            <button onclick="closeModal('deleteEventModal')"
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                ✖
            </button>
            <h2 class="text-xl font-bold mb-4">Eliminar Evento</h2>
            <form id="deleteEventForm" action="{{ route('events.delete') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="eventTitleToDelete" class="block text-sm font-medium text-gray-700 mb-2">Ingrese el título
                        del evento</label>
                    <input type="text" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 mt-1 px-2" id="eventTitleToDelete"
                        name="delete_title" required />
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal('deleteEventModal')"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-2">
                        Cancelar
                    </button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                        Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para abrir y cerrar modales -->
    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
    </script>
</body>

</html>