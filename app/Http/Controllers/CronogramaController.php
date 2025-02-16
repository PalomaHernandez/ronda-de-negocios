<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\Registration; 
use App\Models\User; 
use PDF;

class CronogramaController extends Controller
{
    public function generarPDFtotal($eventId)
    {
        // 🗃️ Obtener reuniones del evento
        $meetings = Meeting::where('event_id', $eventId)
            ->orderBy('time')
            ->get();
    
        // 🚀 Obtener nombres directamente desde la tabla Users
        foreach ($meetings as $meeting) {
            // Buscar nombre para requester_id
            $requester = User::find($meeting->requester_id);
            $requesterName = $requester ? $requester->name : 'Desconocido';
    
            // Buscar nombre para receiver_id
            $receiver = User::find($meeting->receiver_id);
            $receiverName = $receiver ? $receiver->name : 'Desconocido';
    
            // Asignamos los nombres a los objetos
            $meeting->requester_name = $requesterName;
            $meeting->receiver_name = $receiverName;
        }
    
        // 📝 Generar PDF usando la vista 'cronograma'
        $pdf = PDF::loadView('cronograma', compact('meetings', 'eventId'));
    
        // 📄 Mostrar PDF en el navegador
        return $pdf->stream('cronograma.pdf');
        //return $pdf->download('cronograma.pdf');
    }
    public function generarPDFparticipante($eventId, $userId)
{
    // 📌 Buscamos el User por su ID
    $user = User::find($userId);
    if (!$user) {
        return response()->json(['error' => 'Usuario no encontrado'], 404);
    }
    $userName = $user->name; // Guardamos el nombre para pasar a la vista

    // 🗃️ Buscar reuniones donde el usuario sea requester o receiver
    $meetings = Meeting::where('event_id', $eventId)
        ->where(function ($query) use ($userId) {
            $query->where('requester_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->orderBy('time')
        ->get();

    // 🚀 Agregamos nombres y roles según las nuevas reglas
    foreach ($meetings as $meeting) {
        // ✅ Nombre del otro participante usando requester_id o receiver_id directamente
        $otherParticipantId = ($meeting->requester_id == $userId) 
            ? $meeting->receiver_id 
            : $meeting->requester_id;

        $otherUser = User::find($otherParticipantId);
        $meeting->other_participant_name = $otherUser ? $otherUser->name : 'Desconocido';

        // 📌 Nueva lógica de roles según requester_role
        switch ($meeting->requester_role) {
            case 'Compra':
                $meeting->participant_role = ($meeting->requester_id == $userId) ? 'Demandante' : 'Ofertante';
                break;
            case 'Venta':
                $meeting->participant_role = ($meeting->requester_id == $userId) ? 'Ofertante' : 'Demandante';
                break;
            case 'Ambos':
                $meeting->participant_role = 'Ofertante y Demandante';
                break;
            default:
                $meeting->participant_role = 'Desconocido';
        }
    }

    // 📝 Generar PDF usando la vista 'cronogramaIndividual' con el nombre
    $pdf = PDF::loadView('cronogramaIndividual', compact('meetings', 'eventId', 'userName'));

    if (request()->expectsJson()) {
        return $pdf->download('cronograma_individual.pdf');
    }
    // 📄 Mostrar PDF en el navegador
    return $pdf->stream('cronograma_individual.pdf');
}

}
