<?php

namespace App\Repositories;

use App\Models\Registration;
use App\Repositories\Interfaces\RegistrationRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class RegistrationRepositoryImpl implements RegistrationRepository
{
    public function getAll():Collection|array {
        return [];
    }
    public function getRegistrationsByEvent(int $event_id):Collection|array{
        return [];
    }
    public function registerParticipant(int $participant_id):Collection|array{
        return [];
    }
    public function getById(int $id):Registration|Model{
        return Registration::find($id);
    }
    //public function updateRegistration(int $id, array $data): void;
    public function deleteRegistration(int $id): void{

    }
    
}