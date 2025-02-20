<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\Registration;
use App\Models\User;
use App\Repositories\Interfaces\MeetingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Patterns\State\Meeting\MeetingStatus;
use App\Models\Notification;
use App\Models\Event;
use App\Mail\MeetingMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;


class MeetingController extends Controller
{
    public function __construct(private readonly MeetingRepository $repository)
    {
    }

    public function index()
    {
        $meetings = $this->repository->getAll();

        if (!$meetings) {
            return response()->json(['message' => 'There are no meetings.'], 404);
        }

        return response()->json($meetings);
    }

    public function show($id)
    {
        $meeting = $this->repository->getById($id);

        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        return response()->json($meeting);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reason' => 'required|string|max:255',
            'requester_role' => 'required|string',
            'status' => 'required|in:Pendiente,Aceptada,Rechazada',
            'assigned_table' => 'nullable|string|max:50',
            'time' => 'nullable|date_format:H:i',
            'event_id' => 'required|exists:events,id',
            'requester_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $validatedData['status'] = MeetingStatus::tryFrom($validatedData['status']) ?? MeetingStatus::Pending;
        $meeting = $this->repository->create($validatedData);

        $requester = User::find($validatedData['requester_id']);
        $receiver = User::find($validatedData['receiver_id']);

        $receiverRegistration = Registration::where('participant_id', $receiver->id)
            ->where('event_id', $meeting->event_id)
            ->first();
        
        $event = Event::find($meeting->event_id);

        if ($receiver && $requester) {
            $message = "Tienes una nueva solicitud de reunión de parte de " . $requester->name;
            Notification::createNotification($receiverRegistration->id, $message);
            //Mail::to($receiver->email)->send(new MeetingMail($message, 'Pendiente', $event->slug));
        }

        return response()->json($meeting, 201);
    }

    public function update(Request $request, $id)
    {
        $meeting = $this->repository->getById($id);

        $validatedData = $request->validate([
            'status' => 'nullable|string|in:Pendiente,Aceptada,Rechazada',
            'assigned_table' => 'nullable|string|max:50',
            'time' => 'nullable|date_format:H:i',
        ]);

        $previousStatus = $meeting->status;

        if (isset($validatedData['status'])) {
            $validatedData['status'] = MeetingStatus::tryFrom($validatedData['status']) ?? MeetingStatus::Pending;
        }
        $meeting = $this->repository->updateMeeting($id, $validatedData);

        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        if (isset($validatedData['status']) && $previousStatus !== $validatedData['status']) {
            $this->notifyParticipants($meeting, $validatedData['status']->value);
        }

        return response()->json($meeting);
    }

    private function notifyParticipants(Meeting $meeting, string $newStatus)
    {
        $requester = User::find($meeting->requester_id);
        $receiver = User::find($meeting->receiver_id);

        $requesterRegistration = Registration::where('participant_id', $meeting->requester_id)
            ->where('event_id', $meeting->event_id)
            ->first();

        $receiverRegistration = Registration::where('participant_id', $meeting->receiver_id)
            ->where('event_id', $meeting->event_id)
            ->first();
        
        $event = Event::find($meeting->event_id);

        if ($requester && $receiver && $requesterRegistration && $receiverRegistration) {
            $statusText = $newStatus === 'Aceptada' ? 'ha sido aceptada' : 'ha sido rechazada';

            $messageForRequester = "Tu reunión con {$receiver->name} $statusText.";
            $messageForReceiver = "Tu reunión con {$requester->name} $statusText.";

            Notification::createNotification($requesterRegistration->id, $messageForRequester);
            Notification::createNotification($receiverRegistration->id, $messageForReceiver);

            if ($newStatus === 'Aceptada') {
                //Mail::to($requester->email)->send(new MeetingMail($messageForRequester, $newStatus, $event->slug));
                //Mail::to($receiver->email)->send(new MeetingMail($messageForReceiver, $newStatus));
            } elseif ($newStatus === 'Rechazada') {
                //Mail::to($requester->email)->send(new MeetingMail($messageForRequester, $newStatus, $event->slug));
                //Mail::to($receiver->email)->send(new MeetingMail($messageForReceiver, $newStatus));
            }
        }
    }

    public function destroy($id)
    {
        $isDeleted = $this->repository->deleteMeeting($id);

        if (!$isDeleted) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        return response()->json(['message' => 'Meeting deleted successfully'], 200);
    }

    public function getMeetingsByEvent($id)
    {
        $meetings = $this->repository->getMeetingsByEvent($id);

        if ($meetings->isEmpty()) {
            return response()->json(['message' => 'No se encontraron reuniones para este evento.'], 404);
        }

        return response()->json($meetings);
    }

    public function getMeetingsByEventAndUser($event_id, $user_id)
    {
        $meetings = $this->repository->getMeetingsByEventAndUser($event_id, $user_id);

        if (!$meetings) {
            return response()->json(['message' => 'No meetings found for this event and user.'], 404);
        }

        return response()->json($meetings);
    }

    public function acceptAllMeetings($event_id)
    {
        $meetings = $this->repository->acceptAllMeetings($event_id);

        if ($meetings) {
            foreach ($meetings as $meeting) {
                $this->notifyParticipants($meeting, 'Aceptada');
            }
            return response()->json(['message' => 'Todas las reuniones pendientes han sido aceptadas.'], 200);
        }

        return response()->json(['message' => 'No hay reuniones pendientes para aceptar.'], 200);
    }

    public function rejectAllMeetings($event_id)
    {

        $meetings = $this->repository->rejectAllMeetings($event_id);

        if ($meetings) {
            foreach ($meetings as $meeting) {
                $this->notifyParticipants($meeting, 'Rechazada');
            }
            return response()->json(['message' => 'Todas las reuniones pendientes han sido rechazadas.'], 200);
        }

        return response()->json(['message' => 'No hay reuniones pendientes para rechazar.'], 200);
    }

}
