<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\Registration;
use App\Repositories\Interfaces\MeetingRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class MeetingRepositoryImpl implements MeetingRepository
{
    public function getAll():Collection|array{
        return Meeting::with(['event', 'requester', 'receiver'])->get();
    }
    public function getMeetingsByEvent(int $event_id):Collection|array{
        return Meeting::where('event_id', $event_id)->get(); 
    }
    public function getMeetingsForParticipant(int $participant_id):Collection|array{
        return Meeting::where('participant_id', $participant_id)->get(); ;
    }
    public function getMeetingsByEventAndUser(int $event_id, int $user_id): Collection|array
    {
        return Meeting::where('event_id', $event_id)
                     ->where(function ($query) use ($user_id) {
                         $query->where('requester_id', $user_id)
                               ->orWhere('receiver_id', $user_id);
                     })
                     ->get();
    }
    public function getById(int $id):Meeting|Model{
        return Meeting::with(['event', 'requester', 'receiver'])->find($id);
    }
    public function create(array $data): Meeting{
        $registration = Registration::findOrFail($data['requester_id']);
        if($registration){
            $registration->remaining_meetings--;
        }
        return Meeting::create($data);
    }
    public function updateMeeting(int $id, array $data): Meeting|Model{
        $meeting = $this->getById($id);
        $meeting->update($data);
        return $meeting->refresh();
    }

    public function deleteMeeting(int $id): bool{
        return $this->getById($id)->delete();
    }
}