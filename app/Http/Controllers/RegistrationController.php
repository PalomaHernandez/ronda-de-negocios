<?php

namespace App\Http\Controllers;

use App\Actions\UploadImages;
use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Repositories\Interfaces\RegistrationRepository;
use App\Repositories\Interfaces\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{

    public function __construct(private readonly RegistrationRepository $repository, private readonly UserRepository $userRepository)
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

    public function store($event_id)
    {
        $validatedData = request()->validate([
            'interests' => 'nullable|string',
            'products_services' => 'nullable|string',
            'remaining_meetings' => 'nullable|integer',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $validatedData['participant_id'] = $user->id;
        $validatedData['event_id'] = $event_id;

        $this->repository->create($validatedData);

        $deleted_images = request()->input('deleted_files', []);
        if($deleted_images){
            $this->userRepository->deleteImages($deleted_images);
        }
        
        if(request()->hasFile(key: 'gallery')){
            Log::info('Subiendo imagenes');
            UploadImages::execute($user instanceof \App\Models\User ? $user : null, 'gallery');
        }

        return response()->json("Inscripcion exitosa", 201);
    }

    public function update(Request $request, $eventId, $user_id)
    {
        $validatedData = $request->validate([
            'interests' => 'nullable|string',
            'products_services' => 'nullable|string',
            'remaining_meetings' => 'nullable|integer',
        ]);

        $registration = Registration::where('participant_id', $user_id)
                            ->where('event_id', $eventId)
                            ->first();

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        $updatedRegistration = $this->repository->updateRegistration($registration->id, $validatedData);

        if (!$updatedRegistration) {
            return response()->json(['message' => 'Failed to update registration'], 500);
        }
    
        return response()->json($updatedRegistration);
    }

    public function destroy($event_id, $user_id)
    {
        $isDeleted = $this->repository->deleteRegistration($event_id, $user_id);

        if (!$isDeleted) {
            return response()->json(['message' => 'Inscripción no encontrada'], 404);
        }

        return response()->json(['message' => 'Participante eliminado exitosamente.'], 200);
    }

    public function getParticipants($eventId)
    {
        $registrations = $this->repository->getRegistrationsByEvent($eventId);


        if ($registrations->isEmpty()) {
            return response()->json(['message' => 'No se encontraron participantes para este evento.'], 200);
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
                    ?  $registration->participant->logo_path
                    : null,
                'profile_images' => $registration->participant->images->isNotEmpty()
                    ? $registration->participant->images
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
