<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('auth')->group(function (){
    Route::get('/', [EventController::class, 'index'])->name('home');
    Route::post('/events/create', [EventController::class, 'store'])->name('events.create');
    Route::post('/events/delete', [EventController::class, 'destroy'])->name('events.delete');
    Route::get('/create-event', [EventController::class, 'createEventModal'])->name('events.createModal');
    Route::get('/delete-event', [EventController::class, 'deleteEventModal'])->name('events.deleteModal');
});

Route::get('/', [EventController::class, 'index'])->name('home');
    Route::post('/events/create', [EventController::class, 'store'])->name('events.create');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'attempt'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
