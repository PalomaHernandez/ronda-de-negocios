<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">
    <header class="bg-white shadow-md fixed top-0 left-0 w-full z-10">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <h1 class="text-xl font-bold text-gray-800">Rondas UNS Admin</h1>
        </div>
    </header>
    <div class="container mx-auto">
        <div class="flex justify-center items-center h-screen">
            <div class="w-full max-w-md">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4 text-center text-2xl font-bold">{{ __('Iniciar sesión') }}</div>
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
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf
                        <div class="labeled-input">
                            <label for="email"
                                class="label">{{ __('Email') }}</label>
                            <input id="email" type="email"
                                name="email" required>
                        </div>
                        <div class="labeled-input">
                            <label for="password"
                                class="label">{{ __('Contraseña') }}</label>
                            <input id="password" type="password" name="password" required>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="btn">
                                {{ __('Iniciar sesión') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>