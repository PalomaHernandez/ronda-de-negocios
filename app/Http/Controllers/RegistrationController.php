<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index()
    {
        $registrations = Registration::all();
        return response()->json($registrations);
    }

    public function show($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return response()->json($registration);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'participant_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'inscription_date' => 'required|date',
            'interests' => 'nullable|string',
            'products_services' => 'nullable|string',
            'remaining_meetings' => 'nullable|integer',
        ]);

        $registration = Registration::create($validatedData);

        return response()->json($registration, 201);
    }

    public function update(Request $request, $id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        $validatedData = $request->validate([
            'interests' => 'nullable|string',
            'products_services' => 'nullable|string',
            'remaining_meetings' => 'nullable|integer',
        ]);

        $registration->update($validatedData);

        return response()->json($registration);
    }

    public function destroy($id)
    {
        $registration = Registration::find($id);

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        $registration->delete();

        return response()->json(['message' => 'Registration deleted successfully'], 200);
    }
}
