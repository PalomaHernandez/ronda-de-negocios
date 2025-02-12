<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Repositories\Interfaces\RegistrationRepository;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{

    public function __construct(private readonly RegistrationRepository $repository)
    {
    }

    public function index()
    {
        $registrations = $this->repository->getAll();

        if (!$registrations) {
            return response()->json(['message' => 'There are no meetings.'], 404);
        }

        return response()->json($registrations);
    }

    public function show($id)
    {
        $registration = $this->repository->getById($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return response()->json($registration);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'participant_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'inscription_date' => 'required|date', //Fecha automatica en la bd
            'interests' => 'nullable|string',
            'products_services' => 'nullable|string',
            'remaining_meetings' => 'nullable|integer',
        ]);

        $registration = $this->repository->create($validatedData);

        return response()->json($registration, 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'interests' => 'nullable|string',
            'products_services' => 'nullable|string',
            'remaining_meetings' => 'nullable|integer',
        ]);

        $registration = $this->repository->updateRegistration($id, $validatedData);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return response()->json($registration);
    }

    public function destroy($id)
    {
        $isDeleted = $this->repository->deleteRegistration($id);

        if (!$isDeleted) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return response()->json(['message' => 'Registration deleted successfully'], 200);
    }

    public function getParticipants($eventId)
    {
        $registrations = $this->repository->getRegistrationsByEvent($eventId);


        if ($registrations->isEmpty()) {
            return response()->json(['message' => 'No participants found for this event.'], 404);
        }

        $participants = $registrations->map(function ($registration) {
            return [
                'id' => $registration->participant->id,
                'name' => $registration->participant->name,
                'email' => $registration->participant->email,
                'activity' => $registration->participant->activity,
                'location' => $registration->participant->location,
                'website' => $registration->participant->website,
                'logo_path' => $registration->participant->logo_path
                    ? url('storage/' . $registration->participant->logo_path)
                    : null,
                'profile_images' => $registration->participant->images->isNotEmpty()
                    ? $registration->participant->images->transform(function ($file) {
                        $file->file_url = url('storage/' . $file->path);
                        return $file;
                    })
                    : null,
                'interests' => $registration->interests,
                'product_services' => $registration->products_services,
                'remaining_meetings' => $registration->remaining_meetings,
            ];
        });

        return response()->json($participants);
    }

    public function getNotifications($event_id, $user_id) {
        // Usar el método del repositorio
        $notifications = $this->repository->getNotifications($event_id, $user_id);

        // Si no se encuentran notificaciones, devolver un mensaje
        if (!$notifications) {
            return response()->json(['message' => 'No notifications found for this user.'], 404);
        }

        return response()->json($notifications);
    }

}
