@extends('layout.app')

@section('title')
	Iniciar sesión
@endsection

@section('content')
<div class="container mx-auto">
        <div class="flex justify-center items-center h-screen">
            <div class="w-full max-w-md">
                <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                    <div class="mb-4 text-center text-2xl font-bold">{{ __('Iniciar sesión') }}</div>
                    @if (session('error'))
                        <div class="alert alert-danger">
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
                        <div class="flex items-center justify-center">
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
@endsection



