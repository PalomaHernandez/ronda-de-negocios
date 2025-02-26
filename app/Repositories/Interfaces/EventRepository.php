<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

interface EventRepository
{
    public function getAll():Collection|array;
    public function getById(int $id):Event|Model;
    public function getByName(string $name):Event|Model;
    public function create(array $data, User $responsible): Event;
    public function update(int $id, array $data): void;
    public function delete(int $id): void;
    public function isResponsible($user_id, $event_slug): bool;
}
