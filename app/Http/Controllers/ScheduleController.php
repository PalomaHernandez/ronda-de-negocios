<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\Event;
use PDF;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    private function getAllMeetingsData($eventId){
        $meetings = Meeting::where('event_id', $eventId)
        ->where('status', 'Aceptada')
        ->orderBy('time')
        ->get();
 
        foreach ($meetings as $meeting) {

            $requester = User::find($meeting->requester_id);
            $requesterName = $requester ? $requester->name : 'Desconocido';

        
            $receiver = User::find($meeting->receiver_id);
            $receiverName = $receiver ? $receiver->name : 'Desconocido';
            
            $meeting->formatted_time = Carbon::parse($meeting->time)->format('H:i');

            $meeting->requester_name = $requesterName;
            $meeting->receiver_name = $receiverName;
        }

        return $meetings;
    }
    public function generalPDF($eventId)
    {
        $meetings = $this->getAllMeetingsData($eventId);
        $event = Event::find($eventId);

        $pdf = PDF::loadView('schedules.generalSchedule', compact('meetings', 'event'));
        
        if (request()->expectsJson()) {
            return $pdf->download('cronograma_general.pdf');
        }
        
        return $pdf->stream('cronograma.pdf');
    }
    public function emailGeneralPDF($eventId)
    {
        $meetings = $this->getAllMeetingsData($eventId);

        $pdf = PDF::loadView('schedules.generalSchedule', compact('meetings', 'event'));
        
        if (request()->expectsJson()) {
            return $pdf->download('cronograma_general.pdf');
        }
        
        return $pdf->output();
    }

    private function getAllParticipantMeetingsData($eventId, $userId){
        $meetings = Meeting::where('event_id', $eventId)
            ->where('status', 'Aceptada')
            ->where(function ($query) use ($userId) {
                $query->where('requester_id', $userId)
                    ->orWhere('receiver_id', $userId);
            })
            ->orderBy('time')
            ->get();

        foreach ($meetings as $meeting) {
            $otherParticipantId = ($meeting->requester_id == $userId)
                ? $meeting->receiver_id
                : $meeting->requester_id;
            
            $otherUser = User::find($otherParticipantId);
            $meeting->other_participant_name = $otherUser ? $otherUser->name : 'Desconocido';

            $meeting->formatted_time = Carbon::parse($meeting->time)->format('H:i');
            
            switch ($meeting->requester_role) {
                case 'Demandante':
                    $meeting->participant_role = ($meeting->requester_id == $userId) ? 'Demandante' : 'Oferente';
                    break;
                case 'Oferente':
                    $meeting->participant_role = ($meeting->requester_id == $userId) ? 'Oferente' : 'Demandante';
                    break;
                case 'Ambos':
                    $meeting->participant_role = 'Oferente y Demandante';
                    break;
                default:
                    $meeting->participant_role = 'Desconocido';
            }
        }
        return $meetings;
    }

    public function participantPDF($eventId, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        $userName = $user->name;

        $event = Event::find($eventId);

        $meetings = $this->getAllParticipantMeetingsData($eventId, $userId);

        $pdf = PDF::loadView('schedules.individualSchedule', compact('meetings', 'event', 'userName'));

        if (request()->expectsJson()) {
            return $pdf->download('cronograma_individual.pdf');
        }

        return $pdf->stream('cronograma_individual.pdf');
    }

    public function emailParticipantPDF($eventId, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        $userName = $user->name;

        $event = Event::find($eventId);

        $meetings = $this->getAllParticipantMeetingsData($eventId, $userId);

        $pdf = PDF::loadView('schedules.individualSchedule', compact('meetings', 'event', 'userName'));

        if (request()->expectsJson()) {
            return $pdf->download('cronograma_individual.pdf');
        }

        return $pdf->output();
    }

}
