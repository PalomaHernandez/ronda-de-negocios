<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
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

    public function store(StoreEventRequest $request)
    {
        try {
            $validated = $request->validated();
    
            $responsible = $this->userRepository->createOrUpdateResponsible([
                'responsible_email' => $validated['responsible_email'],
                'responsible_password' => $validated['responsible_password'],
            ]);
    
            $event = $this->repository->create([
                'title' => $validated['title'],
                'date' => $validated['date'],
            ], $responsible);
    
            // Enviar correo de confirmación al responsable
            Mail::to($responsible->email)->send(
                new EventoCreadoMail(
                    $event->title,
                    $event->slug,
                    $responsible->email,
                    $validated['responsible_password']
                )
            );
    
            return redirect()->route('home')->with('success', 'Evento creado exitosamente.');
    
        } catch (\Exception $e) {
            \Log::error('Error al crear el evento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Hubo un error al crear el evento.');
        }
    }

    public function update(UpdateEventRequest $request, int $id)
    {

        $validator = $request->validated();

        $this->repository->update($id, $validator);

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

    public function startMatchingPhase(Request $request, int $event_id){
        $event = Event::find($event_id);

        if (!$event) {
            return response()->json(['message' => 'Evento no encontrado'], 404);
        }

        $validated = $request->validate([
            'starts_at' => 'nullable|date_format:H:i',
            'ends_at' => 'nullable|date_format:H:i|after_or_equal:starts_at',
            'meeting_duration' => 'nullable|integer|min:1',
            'time_between_meetings' => 'nullable|integer|min:0',
        ]);
    
        // Actualizar solo si se envían valores
        $event->fill(array_filter($validated));

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
        $event->tables_needed = $tables;
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