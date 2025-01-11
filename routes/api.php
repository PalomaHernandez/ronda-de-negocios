<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/test-connection', function () {
    return response()->json(['message' => 'Backend connection successful!'], 200);
});
Route::get('/', [EventController::class, 'index'])->name('home');
Route::get('/', [EventController::class, 'update'])->name('event.update');