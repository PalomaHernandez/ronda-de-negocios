<?php

namespace App\Repositories;

use App\Models\Registration;
use App\Models\Event;
use App\Repositories\Interfaces\RegistrationRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

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

    public function userRegistration(string $slug): Registration|null{
		$event_id = Event::where('slug', $slug)->first()->id;

        $user = Auth::user();

        return Registration::where('participant_id', $user->id)
            ->where('event_id', $event_id)->first();
	}

    public function updateRegistration(int $id, array $data):Registration|Model{
        $registration = $this->getById($id);
        $registration->update($data);
        return $registration->refresh();
    }
    public function deleteRegistration(int $event_id, int $user_id): bool{
        return Registration::where('event_id', $event_id)
                           ->where('participant_id', $user_id)
                           ->delete();
    }

    public function getNotifications(int $event_id, int $user_id): Collection {
        return Registration::where('event_id', $event_id)
                           ->where('participant_id', $user_id)
                           ->first()?->notifications ?? collect();
    }

}