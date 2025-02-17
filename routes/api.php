<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\CronogramaController;

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('events')->group(function () {
        Route::patch('/{id}', [EventController::class, 'update']);
        Route::post('/{eventId}/end-matching', [EventController::class, 'endMatchingPhase']);
        Route::get('/{id}/participants', [RegistrationController::class, 'getParticipantsByEvent']);
        Route::post('/{id}/registration', [RegistrationController::class, 'store']);
        Route::get('/{id}/participants', [RegistrationController::class, 'getParticipants']);
        Route::get('/{event_id}/meetings', [MeetingController::class, 'getMeetingsByEvent']);
        Route::get('/{event_id}/meetings/{user_id}', [MeetingController::class, 'getMeetingsByEventAndUser']);
        Route::get('/{event_id}/notifications/{user_id}', [RegistrationController::class, 'getNotifications']);
        Route::get('/{slug}/is-registered', [UserController::class, 'isRegistered']);
        Route::delete('/{id}/participants/{user_id}', [RegistrationController::class, 'destroy']);
    });
    Route::get('/cronograma/{eventId}', [CronogramaController::class, 'generarPDFtotal']);
    Route::get('/cronograma/{eventId}/{userId}',[CronogramaController::class, 'generarPDFparticipante']);
    Route::post('/meetings', [MeetingController::class, 'store']);
    Route::post('/meetings/{event_id}/accept-all', [MeetingController::class, 'acceptAllMeetings']);
    Route::post('/meetings/{event_id}/reject-all', [MeetingController::class, 'rejectAllMeetings']);
    Route::patch('/meetings/{id}', [MeetingController::class, 'update']);
    Route::delete('/meetings/{id}', [MeetingController::class, 'destroy']);
    Route::get('/user', [UserController::class, 'index']);
    Route::patch('/user/profile', [UserController::class, 'update']);
    Route::post('/logout', [LoginController::class, 'logout']);
});

Route::get('events/{slug}', [EventController::class, 'showByName']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);

