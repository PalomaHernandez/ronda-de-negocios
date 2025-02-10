<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\MeetingController;

Route::get('/test-connection', function () {
    return response()->json(['message' => 'Backend connection successful!'], 200);
});

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{slug}', [EventController::class, 'showByName']);
    Route::patch('/{id}', [EventController::class, 'update']); 
    Route::delete('/{id}', [EventController::class, 'destroy']);
    Route::get('/{id}/participants', [RegistrationController::class, 'getParticipantsByEvent']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    Route::post('/logout', [LoginController::class, 'logout']);
});
//Route::middleware('guest')->post('/register', [LoginController::class, 'store']);
//Route::middleware(['web'])->group(function () {
//Route::post('/login', [LoginController::class, 'login']);

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);

Route::get('/events/{id}/participants', [RegistrationController::class, 'getParticipants']); 
Route::get('/events/{event_id}/meetings', [MeetingController::class, 'getMeetingsByEvent']);
Route::get('/events/{event_id}/meetings/{user_id}', [MeetingController::class, 'getMeetingsByEventAndUser']);
//Route::get('/events/{event_id}/notifications/{user_id}', [RegistrationController::class, 'getNotifications']); 

Route::post('/meetings', [MeetingController::class, 'store']);
