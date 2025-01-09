<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Repositories\Interfaces\MeetingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeetingController extends Controller{
    public function __construct(private readonly MeetingRepository $repository){}
    
    public function index()
    {
        $meetings = Meeting::with(['event', 'requester', 'receiver'])->get();

        return response()->json($meetings);
    }

    public function show($id)
    {
        $meeting = Meeting::with(['event', 'requester', 'receiver'])->find($id);

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
            'status' => 'required|string',
            'assigned_table' => 'nullable|string|max:50',
            'time' => 'nullable|date_format:Y-m-d H:i:s',
            'event_id' => 'required|exists:events,id',
            'requester_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $meeting = Meeting::create($validatedData);

        return response()->json($meeting, 201);
    }

    public function update(Request $request, $id)
    {
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        $validatedData = $request->validate([
            'status' => 'nullable|string',
            'assigned_table' => 'nullable|string|max:50',
            'time' => 'nullable|date_format:Y-m-d H:i:s',
        ]);

        $meeting->update($validatedData);

        return response()->json($meeting);
    }

    public function destroy($id)
    {
        $meeting = Meeting::find($id);

        if (!$meeting) {
            return response()->json(['message' => 'Meeting not found'], 404);
        }

        $meeting->delete();

        return response()->json(['message' => 'Meeting deleted successfully'], 200);
    }
}
