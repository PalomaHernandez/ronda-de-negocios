<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use App\Repositories\Interfaces\MeetingRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Patterns\State\Meeting\MeetingStatus;

class MeetingController extends Controller{
    public function __construct(private readonly MeetingRepository $repository){}
    
    public function index()
    {
        $meetings = $this->repository->getAll();

        if(!$meetings){
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
            'time' => 'nullable|date_format:Y-m-d H:i:s',
            'event_id' => 'required|exists:events,id',
            'requester_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id',
        ]);

        $validatedData['status'] = MeetingStatus::tryFrom($validatedData['status']) ?? MeetingStatus::Pending;
        $meeting = $this->repository->create($validatedData);

        return response()->json($meeting, 201);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'status' => 'nullable|string|in:Pendiente,Aceptada,Rechazada',
            'assigned_table' => 'nullable|string|max:50',
            'time' => 'nullable|date_format:Y-m-d H:i:s',
        ]);
        if (isset($validatedData['status'])) {
            $validatedData['status'] = MeetingStatus::tryFrom($validatedData['status']) ?? MeetingStatus::Pending;
        }
       $meeting = $this->repository->updateMeeting($id,$validatedData);

       if (!$meeting) {
        return response()->json(['message' => 'Meeting not found'], 404);
       }

        return response()->json($meeting);
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
            return response()->json(['message' => 'No meetings found for this event.'], 404);
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
    
}
