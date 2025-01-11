<?php

namespace App\Repositories;

use App\Models\Registration;
use App\Repositories\Interfaces\RegistrationRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class RegistrationRepositoryImpl implements RegistrationRepository
{
    public function getAll():Collection|array {
        return Registration::all();
    }
    public function getRegistrationsByEvent(int $event_id):Collection|array{
        return Registration::where('event_id', $event_id)->get();
    }
    public function create(array $data):Registration|Model{
        return Registration::create($data);
    }
    public function getById(int $id):Registration|Model{
        return Registration::find($id);
    }
    public function updateRegistration(int $id, array $data):Registration|Model{
        $registration = $this->getById($id);
        $registration->update($data);
        return $registration->refresh();
    }
    public function deleteRegistration(int $id): bool{
        return $this->getById($id)->delete();
    }
    
}