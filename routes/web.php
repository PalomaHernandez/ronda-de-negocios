<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ScheduleController;

Route::middleware(['auth', 'admin'])->group(function (){
    Route::get('/', [EventController::class, 'index'])->name('home');
    Route::post('/events/create', [EventController::class, 'store'])->name('events.create');
    Route::delete('/events/delete/{id}', [EventController::class, 'destroy'])->name('events.delete');
    Route::get('/create-event', [EventController::class, 'createEventModal'])->name('events.createModal');
    Route::get('/delete-event', [EventController::class, 'deleteEventModal'])->name('events.deleteModal');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/cronograma/{eventId}', [ScheduleController::class, 'generalPDF']);
    Route::get('/cronograma/{eventId}/{userId}',[ScheduleController::class, 'participantPDF']);
    Route::patch('/events/start-matching/{eventId}', [EventController::class, 'startMatchingPhase'])->name('events.startMatching');
    Route::patch('/events/end-matching/{eventId}', [EventController::class, 'endMatchingPhase'])->name('events.endMatching');
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

Route::get('/debug-files', function () {
    return response()->json(scandir(public_path('storage/images')));
});
