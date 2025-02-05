<?php

namespace App\Repositories;

use App\Actions\UploadFiles;
use App\Models\Event;
use App\Models\User;
use App\Repositories\Interfaces\EventRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Patterns\State\Event\EventStatus;

class EventRepositoryImpl implements EventRepository
{
    public function getAll():Collection|array{
        return Event::all();
    }
    public function getById(int $id):Event|Model{
        return Event::find($id);
    }
    public function create(array $data, User $responsible): void{
        Event::create([
            'title' => $data['title'],
            'date' => $data['date'],
            'status' => EventStatus::Registration,
            'responsible_id' => $responsible->id,
        ]);
    }
    public function update(int $id, array $data): void{
        $event= $this->getById($id);

        $event->update($data);

        dd(request()->all());

        if (request()->hasFile('logo')) {
			UploadFiles::execute($event, 'logo');
		}

        if (request()->hasFile('documents')) {
			UploadFiles::execute($event, 'documents');
		}
    }
    public function delete(int $id): void{
        $this->getById($id)->delete();
    }

    public function deleteByTitle(string $event_title): void{
        Event::where('title', $event_title)->delete();
    }

    public function addFileToEvent(int $event_id, array $fileData): void{}
    public function getFilesByEvent(int $event_id): void{}

    public function deleteFileFromEvent(int $event_id): void{}
}
