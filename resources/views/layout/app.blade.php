<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>@yield('title') | {{ config('app.name') }}</title>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
		@vite(['resources/css/app.css', 'resources/js/app.js'])
	</head>
	<body class="antialiased">
        <header class="bg-sky-900 shadow-md fixed top-0 left-0 w-full z-10 h-[8vh] flex items-center px-6 overflow-hidden">
            <div class="flex justify-between items-center w-full">
                <div class="h-15 w-60 flex items-center !p-1">
                    <img src="/images/rondas-uns.png" alt="Rondas UNS" class="h-15 w-60">
                </div>
                @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-white font-bold text-sm sm:text-base flex items-center space-x-2">
                        <span class="font-bold">Cerrar sesión</span>
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
                @endauth
            </div>
        </header>
		
        <div class="flex-grow h-screen flex flex-col">
            @yield('content')
        </div>
	</body>
</html>
