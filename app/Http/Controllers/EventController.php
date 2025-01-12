<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Patterns\State\Event\EventStatus;
use App\Repositories\Interfaces\EventRepository;
use App\Repositories\Interfaces\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EventController extends Controller {

    public function __construct(private readonly EventRepository $repository, private readonly UserRepository $userRepository){}

    public function index()
    {
        $events = $this->repository->getAll();
        return view('home', compact('events'));
    }
/*
    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event);
    }
*/
public function showByName($name)
{
    $event = Event::where('title', $name)->first();

    if (!$event) {
        return response()->json(['message' => 'Event not found'], 404);
    }

    return response()->json($event);
}

/*
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date_format:H:i:s',
            'ends_at' => 'nullable|date_format:H:i:s',
            'date' => 'required|date',
            'meeting_duration' => 'nullable|date_format:H:i:s',
            'time_between_meetings' => 'nullable|date_format:H:i:s',
            'inscription_end_date' => 'nullable|date',
            'matching_end_date' => 'nullable|date',
            'logo_path' => 'nullable|string',
            'status' => 'required|string',
        ]);

        $event = Event::create($validatedData);

        return response()->json($event, 201);
    }
*/
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
    
        return redirect()->back()
            ->withErrors($errors)
            ->with('error', $errorMessage);
    }
    

    $responsible = $this->userRepository->createOrUpdateResponsible($userValidated);

    $this->repository->create($validated, $responsible);
    
    return redirect()->route('home')->with('success', 'Evento creado exitosamente.');
}

    public function update(Request $request, int $id)
    {
        $validatedData = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date_format:H:i:s',
            'ends_at' => 'nullable|date_format:H:i:s',
            'date' => 'nullable|date',
            'meeting_duration' => 'nullable|date_format:H:i:s',
            'time_between_meetings' => 'nullable|date_format:H:i:s',
            'inscription_end_date' => 'nullable|date',
            'matching_end_date' => 'nullable|date',
            'logo_path' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $this->repository->update($id, $validatedData);

        //return response()->json($event);
    }


    public function destroy(Request $request)
    {
        try {
            $request->validate([
                'delete_title' => 'required|string',
            ]);    
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors()) 
                ->with('error', 'Debe ingresar un titulo de evento válido.');
        }

        $this->repository->deleteByTitle(
            $request->delete_title
        );

        return redirect()->route('home')->with('success', 'Evento eliminado exitosamente.');
    }

}