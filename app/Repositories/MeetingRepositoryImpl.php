<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\User;
use App\Models\Event;
use App\Models\Registration;
use App\Patterns\State\Meeting\MeetingStatus;
use App\Models\Notification;
use App\Repositories\Interfaces\MeetingRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Mail\MeetingMail;
use Illuminate\Support\Facades\Mail;

class MeetingRepositoryImpl implements MeetingRepository
{
    public function getAll(): Collection|array
    {
        return Meeting::with(['event', 'requester', 'receiver'])->get();
    }
    public function getMeetingsByEvent(int $event_id): Collection|array
    {
        return Meeting::where('event_id', $event_id)->get();
    }
    public function getMeetingsForParticipant(int $participant_id): Collection|array
    {
        return Meeting::where('participant_id', $participant_id)->get();
        ;
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
    public function getById(int $id): Meeting|Model
    {
        return Meeting::with(['event', 'requester', 'receiver'])->find($id);
    }
    public function create(array $data): Meeting
    {
        return Meeting::create($data);
    }
    public function updateMeeting(int $id, array $data): Meeting|Model
    {
        $meeting = $this->getById($id);
        $meeting->update($data);
        return $meeting->refresh();
    }

    public function deleteMeeting(int $id): bool
    {
        $meeting = $this->getById($id);

        if (!$meeting) {
            return false;
        }

        $requester = User::find($meeting->requester_id);
        $receiver = User::find($meeting->receiver_id);

        $receiverRegistration = Registration::where('participant_id', $receiver->id)
            ->where('event_id', $meeting->event_id)
            ->first();
        
        $event = Event::find($meeting->event_id);

        if ($receiverRegistration) {
            $message = "La reunión con {$requester->name} ha sido cancelada";
            Notification::createNotification($receiverRegistration->id, $message);
            Mail::to($receiver->email)->send(new MeetingMail($message, 'Cancelada', $event->slug));
        }

        return $meeting->delete();
    }

    public function acceptAllMeetings(int $event_id): Collection|array {
        $meetings = Meeting::where('event_id', $event_id)
        ->where('status', MeetingStatus::Pending)->get();

        $meetings->each(function ($meeting) {
            $meeting->update(['status' => MeetingStatus::Accepted]);
        });

        return $meetings;
    }

    public function rejectAllMeetings(int $event_id): Collection|array {
        $meetings = Meeting::where('event_id', $event_id)
        ->where('status', MeetingStatus::Pending)->get();

        $meetings->each(function ($meeting) {
            $meeting->update(['status' => MeetingStatus::Rejected]);
        });

        return $meetings;
    }
}