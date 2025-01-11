<?php

namespace App\Repositories\Interfaces;

use App\Models\Registration;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RegistrationRepository
{
    public function getAll():Collection|array;
    public function getRegistrationsByEvent(int $event_id):Collection|array;
    public function create(array $data):Registration|Model;
    public function getById(int $id):Registration|Model;
    public function updateRegistration(int $id, array $data):Registration|Model;
    public function deleteRegistration(int $id): bool;

}