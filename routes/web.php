<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CronogramaController;

Route::middleware(['auth', 'admin'])->group(function (){
    Route::get('/', [EventController::class, 'index'])->name('home');
    Route::post('/events/create', [EventController::class, 'store'])->name('events.create');
    Route::delete('/events/delete/{id}', [EventController::class, 'destroy'])->name('events.delete');
    Route::get('/create-event', [EventController::class, 'createEventModal'])->name('events.createModal');
    Route::get('/delete-event', [EventController::class, 'deleteEventModal'])->name('events.deleteModal');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/cronograma/{eventId}', [CronogramaController::class, 'generarPDFtotal']);
    Route::get('/cronograma/{eventId}/{userId}',[CronogramaController::class, 'generarPDFparticipante']);
    Route::patch('/events/end-matching/{eventId}', [EventController::class, 'endMatchingPhase'])->name('events.endMatching');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
