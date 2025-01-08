<?php

use App\Models\Event;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


Route::get('/', [EventController::class, 'index'])->name('home');
Route::post('/events/create', [EventController::class, 'store'])->name('events.create');
Route::post('/events/delete', [EventController::class, 'destroy'])->name('events.delete');

Route::get('/', function () {
    $events = Event::all(); // Obtener todos los eventos

    return view('home', compact('events')); // Pasar los eventos a la vista home.blade.php
});

