<?php

namespace App\Http\Controllers;

use App\Actions\UploadImages;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRegistrationRequest;
use App\Models\Registration;
use App\Repositories\Interfaces\RegistrationRepository;
use App\Repositories\Interfaces\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;

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

    public function store(StoreRegistrationRequest $request, $event_id)
    {
        $event = Event::find($event_id);
        $registeredCount = $event->registrations()->count();

        if ($registeredCount >= $event->max_participants) {
            return response()->json(['message' => 'El evento ya alcanzó el límite de participantes.'], 403);
        }

        $validatedData = $request->validated();

        $user = Auth::user();
        $validatedData['participant_id'] = $user->id;
        $validatedData['event_id'] = $event_id;

        $registration = $this->repository->create($validatedData);

        $deleted_images = request()->input('deleted_files', []);
        if($deleted_images){
            $this->userRepository->deleteImages($deleted_images);
        }
        
        if(request()->hasFile(key: 'gallery')){
            UploadImages::execute($user instanceof \App\Models\User ? $user : null, 'gallery');
        }
        if($registration){
            return response()->json(
                [
                    'message' => "Inscripción exitosa",
                    'registered' => 'true',
                    'registration' => $registration,
                ]
            );
        }
        return response()->json(['registered' => false]);
    }

    public function update(StoreRegistrationRequest $request, $eventId, $user_id)
    {
        $validatedData = $request->validated();

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
        $event = Event::with('participants.images')->find($eventId);

        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado.'], 404);
        }

        if ($event->participants->isEmpty()) {
            return response()->json(['message' => 'No se encontraron participantes para este evento.'], 200);
        }

        $participants = $event->participants->map(function ($participant) {
            return [
                'id' => $participant->id,
                'name' => $participant->name,
                'email' => $participant->email,
                'activity' => $participant->activity,
                'location' => $participant->location,
                'website' => $participant->website,
                'logo_url' => $participant->logo_url ?: null,
                'profile_images' => $participant->images->isNotEmpty() ? $participant->images : null,
                'interests' => $participant->pivot->interests ?? null,
                'products_services' => $participant->pivot->products_services ?? null,
                'remaining_meetings' => $participant->pivot->remaining_meetings ?? 0,
            ];
        });

        return response()->json($participants);
    }

    public function getNotifications($event_id, $user_id) {
        $notifications = $this->repository->getNotifications($event_id, $user_id);

        if (!$notifications) {
            return response()->json(['message' => 'No notifications found for this user.'], 404);
        }

        return response()->json($notifications);
    }

}
