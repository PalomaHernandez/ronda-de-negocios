<?php

use App\Models\Event;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


Route::get('/', [EventController::class, 'index'])->name('home');
Route::post('/events/create', [EventController::class, 'store'])->name('events.create');
Route::post('/events/delete', [EventController::class, 'destroy'])->name('events.delete');


