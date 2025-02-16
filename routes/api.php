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
    Route::get('/user', [UserController::class, 'index']);

    Route::post('/logout', [LoginController::class, 'logout']);
    Route::post('/events/{id}/registration', [RegistrationController::class, 'store']);
    
    Route::delete('/events/{id}/participants/{user_id}', [RegistrationController::class, 'destroy']); 
});

Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);

Route::get('/events/{id}/participants', [RegistrationController::class, 'getParticipants']); 
Route::get('/events/{event_id}/meetings', [MeetingController::class, 'getMeetingsByEvent']);
Route::get('/events/{event_id}/meetings/{user_id}', [MeetingController::class, 'getMeetingsByEventAndUser']);
Route::get('/events/{event_id}/notifications/{user_id}', [RegistrationController::class, 'getNotifications']); 

Route::get('/events/{slug}/is-registered/{user_id}', [UserController::class, 'isRegistered']);

Route::middleware('auth:sanctum')->patch('/user/profile', [UserController::class, 'update']);

Route::post('/meetings', [MeetingController::class, 'store']);
Route::patch('/meetings/{id}', [MeetingController::class, 'update']);
Route::delete('/meetings/{id}', [MeetingController::class, 'destroy']);
