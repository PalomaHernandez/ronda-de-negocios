<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Repositories\Interfaces\MeetingRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class MeetingRepositoryImpl implements MeetingRepository
{
    public function getAll():Collection|array{
        return [];
    }
    public function getMeetingsByEvent(int $event_id):Collection|array{
        return [];
    }
    public function getMeetingsForParticipant(int $participant_id):Collection|array{
        return [];
    }
    public function getById(int $id):Meeting|Model{
        return Meeting::find($id);
    }
    public function create(array $data): void{

    }
    public function updateMeeting(int $id, array $data): void{

    }
    public function deleteMeeting(int $id): void{
        
    }
}