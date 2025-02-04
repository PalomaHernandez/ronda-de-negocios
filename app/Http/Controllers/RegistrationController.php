<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Repositories\Interfaces\RegistrationRepository;
use Illuminate\Http\Request;

class RegistrationController extends Controller {

    public function __construct(private readonly RegistrationRepository $repository){}

    public function index()
    {
        $registrations = $this->repository->getAll();

        if(!$registrations){
            return response()->json(['message' => 'There are no meetings.'], 404);
        }

        return response()->json($registrations);
    }

    public function show($id)
    {
        $registration = Registration::find($id);

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

        $registration = $this->repository->updateRegistration($id,$validatedData);

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

    public function getParticipantsByEvent($eventId)
    {
        $participants = Registration::where('event_id', $eventId)
            ->with([
                'participant:id,name,email,activity,location,website,logo_path', // Selecciona solo los campos necesarios
                'participant.images:path,user_id'  // Agrega las imágenes del usuario
            ])
            ->get()
            ->pluck('participant');
    
        if ($participants->isEmpty()) {
            return response()->json(['message' => 'No participants found'], 404);
        }
    
        return response()->json($participants);
    }

}
