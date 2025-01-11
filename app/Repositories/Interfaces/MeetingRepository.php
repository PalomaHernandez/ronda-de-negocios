<?php

namespace App\Repositories\Interfaces;

use App\Models\Meeting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface MeetingRepository
{
    public function getAll():Collection|array;
    public function getMeetingsByEvent(int $event_id):Collection|array;
    public function getMeetingsForParticipant(int $participant_id):Collection|array;
    public function getById(int $id):Meeting|Model;
    public function create(array $data):Meeting|Model;
    public function updateMeeting(int $id, array $data):Meeting|Model;
    public function deleteMeeting(int $id): bool;
}