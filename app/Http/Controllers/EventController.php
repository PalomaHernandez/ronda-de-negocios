<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        return response()->json($event);
    }

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
            'status' => 'nullable|string',
        ]);

        $event = Event::create($validatedData);

        return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

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

        $event->update($validatedData);

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}