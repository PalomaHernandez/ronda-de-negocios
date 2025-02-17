<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Patterns\State\Event\EventStatus;
use App\Repositories\Interfaces\EventRepository;
use App\Repositories\Interfaces\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Meeting;
use Carbon\Carbon;
use App\Mail\EventoCreadoMail;
use Illuminate\Support\Facades\Mail;

class EventController extends Controller {

    public function __construct(private readonly EventRepository $repository, private readonly UserRepository $userRepository){}

    public function index()
    {
        $events = $this->repository->getAll();
        return view('home', compact('events'));
    }

    public function createEventModal()
    {
        return view('modals.create-event');
    }

    public function deleteEventModal()
    {
        return view('modals.delete-event');
    }

    public function showByName($slug)
    {
        $event = $this->repository->getByName($slug);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        
        return response()->json($event);
    }

    public function store(Request $request)
    {
        try {
            $validated = request()->validate([
                'title' => 'required|string|max:255|unique:events,title',
                'date' => 'required|date',
            ], [
                'title.required' => 'El título del evento es obligatorio',
                'title.unique' => 'El título del evento ya está en uso',
                'date.required' => 'La fecha del evento es obligatoria',
            ]);
        
            $userValidated = request()->validate([
                'responsible_email' => 'required|email',
                'responsible_password' => 'required|string|min:8|confirmed',
            ], [
                'responsible_email.required' => 'El mail del responsable es obligatorio',
                'responsible_password.required' => 'La contraseña del responsable es obligatoria',
                'responsible_password.confirmed' => 'La confirmación de la contraseña no coincide',
                'responsible_password.min' => 'La contraseña debe tener mínimo 8 caracteres',
            ]);
            
        } catch (ValidationException $e) {
            $errors = $e->errors();
        
            $translatedFields = [
                'title' => 'Título',
                'date' => 'Fecha',
                'responsible_email' => 'Correo del Responsable',
                'responsible_password' => 'Contraseña del Responsable',
            ];
        
            $errorMessage = "Errores encontrados: ";
            foreach ($errors as $field => $messages) {
                $fieldName = $translatedFields[$field] ?? $field; 
                $errorMessage .= ucfirst($fieldName) . ": " . implode(", ", $messages);
            }
            Log::error($errorMessage);
            return redirect()->back()
                ->withErrors($errors)
                ->with('error', $errorMessage);
        }
        

        $responsible = $this->userRepository->createOrUpdateResponsible($userValidated);

        $event = $this->repository->create($validated, $responsible);
        
        

        Mail::to($responsible->email)->send(
            new EventoCreadoMail(
                $event->title, 
                $event->slug, 
                $responsible->email, 
                $userValidated['responsible_password']
            )
        );
        return redirect()->route('home')->with('success', 'Evento creado exitosamente.');
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255|unique:events,title,'.$id,
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date_format:H:i:s',
            'ends_at' => 'nullable|date_format:H:i:s',
            'date' => 'nullable|date',
            'meeting_duration' => 'nullable|integer|min:1',
            'time_between_meetings' => 'nullable|integer|min:0',
            'inscription_end_date' => 'nullable|date',
            'matching_end_date' => 'nullable|date',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,txt'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422)
                ->header('Access-Control-Allow-Origin', '*');
        }

        $this->repository->update($id, $validator->validated());

        return response()->json(['message' => 'Evento actualizado correctamente'])
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function destroy($event_id)
    {
        
        $this->repository->delete(
            $event_id
        );

        return redirect()->route('home')->with('success', 'Evento eliminado exitosamente.');
    }

    public function startMatchingPhase(int $event_id){
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $event->status = EventStatus::Matching;
        $event->save();

        return redirect()->route('home')->with('success', 'Fase de matching iniciada correctamente.');
    }

    public function endMatchingPhase(int $eventId)
    {
        $event = Event::find($eventId);
    
        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }
    
        if ($event->starts_at && $event->ends_at) {
            $start = Carbon::parse($event->starts_at);
            $end = Carbon::parse($event->ends_at);
            $durationInMinutes = $start->diffInMinutes($end);
        } else {
            $durationInMinutes = null; 
        }
    
        $meeting_duration = $event->meeting_duration; 
        $time_between_meetings = $event->time_between_meetings; 
        $tables = 0; 
    
        if ($durationInMinutes !== null) {
            $AmmountOfMeetings = intdiv($durationInMinutes, $meeting_duration + $time_between_meetings);
            $remainder = $durationInMinutes % ($meeting_duration + $time_between_meetings);
            if ($remainder >= $meeting_duration) {
                $AmmountOfMeetings++;
            }
        } else {
            $AmmountOfMeetings = null;
        }
    
        $meetings = Meeting::where('event_id', $eventId)->get();
        $reuniones = $meetings->map(function ($meeting) {
            return [$meeting->requester_id, $meeting->receiver_id];
        })->toArray();
    
        $agenda = $this->asignarReuniones(
            $reuniones, 
            $event->starts_at, 
            $event->ends_at, 
            $meeting_duration, 
            $time_between_meetings
        );

        $tables = collect($agenda)->max('mesa') ?? 0;

        foreach ($agenda as $item) {
            $meeting = $meetings->firstWhere(function ($m) use ($item) {
                return (
                    ($m->requester_id === $item['participante1'] && $m->receiver_id === $item['participante2']) ||
                    ($m->requester_id === $item['participante2'] && $m->receiver_id === $item['participante1'])
                );
            });
    
            if ($meeting) {
                $meeting->assigned_table = $item['mesa'];
                $meeting->time = $item['horario'];
                $meeting->assigned_table = $item['mesa'];
                $meeting->save();
            }
        }
    
        $event->status = EventStatus::Ended;
        //$event->tables = $tables;
        $event->save();

        if(request()->expectsJson()){
            return response()->json([
                'message' => 'Fase de matching terminada correctamente.',
                'duration_in_minutes' => $durationInMinutes,
                'AmmountOfMeetings' => $AmmountOfMeetings,
                'tables' => $tables,
                'agenda' => $agenda,
            ]);
        }
        
        return redirect()->route('home')->with('success', 'Fase de matching terminada correctamente.');
    }
    

    private function asignarReuniones($reuniones, $horaInicio, $horaFin, $duracionReunion, $descanso) {

        usort($reuniones, function($a, $b) {
            return $a[0] === $b[0] ? $a[1] <=> $b[1] : $a[0] <=> $b[0];
        });
    
        $horarioActual = strtotime($horaInicio);
        $horaFin = strtotime($horaFin);
        $agenda = [];
        $reunionesRealizadas = [];
        $participantesOcupados = [];
    
        while ($horarioActual + ($duracionReunion * 60) <= $horaFin) {
            foreach ($reuniones as $reunion) {
                list($p1, $p2) = $reunion;
                $horaClave = date('H:i', $horarioActual);
    
                if (!isset($participantesOcupados[$horaClave])) {
                    $participantesOcupados[$horaClave] = [];
                }
    
                if (
                    !in_array([$p1, $p2], $reunionesRealizadas, true) &&
                    !in_array([$p2, $p1], $reunionesRealizadas, true) &&
                    !in_array($p1, $participantesOcupados[$horaClave]) &&
                    !in_array($p2, $participantesOcupados[$horaClave])
                ) {
                    $mesa = floor(count($participantesOcupados[$horaClave]) / 2) + 1;
                    $agenda[] = [
                        'participante1' => $p1,
                        'participante2' => $p2,
                        'horario' => $horaClave,
                        'mesa' => $mesa
                    ];
    
                    $participantesOcupados[$horaClave][] = $p1;
                    $participantesOcupados[$horaClave][] = $p2;
                    $reunionesRealizadas[] = [$p1, $p2];
                    $reunionesRealizadas[] = [$p2, $p1];
                }
            }
            $horarioActual += ($duracionReunion + $descanso) * 60;
        }
        
        return $agenda;
    }
    
}