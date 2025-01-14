<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\auth\LoginController;

Route::get('/test-connection', function () {
    return response()->json(['message' => 'Backend connection successful!'], 200);
});

Route::prefix('events')->group(function () {
    // Obtener todos los eventos
    Route::get('/', [EventController::class, 'index']); // No es necesario el nombre si no es una vista

    // Obtener un evento específico por nombre
    Route::get('/{name}', [EventController::class, 'showByName']); // Nueva ruta para obtener evento por nombre

    // Actualizar un evento (si es necesario)
    Route::put('/{id}', [EventController::class, 'update']); // PUT para actualización de eventos

    // Eliminar un evento (si es necesario)
    Route::delete('/{id}', [EventController::class, 'destroy']); // DELETE para eliminar un evento
});

//Route::middleware('guest')->post('/register', [LoginController::class, 'store']);
//Route::middleware(['web'])->group(function () {
    Route::post('/login', [LoginController::class, 'login']);

    
