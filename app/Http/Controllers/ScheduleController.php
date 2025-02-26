<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Models\User;
use App\Models\Event;
use PDF;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function generalPDF($eventId)
    {
        $meetings = Meeting::where('event_id', $eventId)
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

        $pdf = PDF::loadView('schedules.generalSchedule', compact('meetings', 'eventId'));

        
        return $pdf->stream('cronograma.pdf');
    }
    public function participantPDF($eventId, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }
        $userName = $user->name;

        $event = Event::find($eventId);

        $meetings = Meeting::where('event_id', $eventId)
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

        $pdf = PDF::loadView('schedules.individualSchedule', compact('meetings', 'event', 'userName'));

        if (request()->expectsJson()) {
            return $pdf->download('cronograma_individual.pdf');
        }

        return $pdf->stream('cronograma_individual.pdf');
    }

}
