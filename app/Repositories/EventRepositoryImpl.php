<?php

namespace App\Repositories;

use App\Actions\UploadFiles;
use App\Models\Event;
use App\Models\User;
use App\Repositories\Interfaces\EventRepository;
use App\Services\CloudinaryService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\Patterns\State\Event\EventStatus;
use App\Models\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class EventRepositoryImpl implements EventRepository
{
    public function getAll():Collection|array{
        return Event::all();
    }
    public function getById(int $id):Event|Model{
        return Event::find($id);
    }
    public function getByName(string $name):Event|Model{
       $event = Event::with('files')->where('slug', $name)->first();
       if (!$event) {
        throw new \Exception("Evento '$name' no encontrado");
        }
        return $event;
    }
    public function create(array $data, User $responsible): Event{
        return Event::create([
            'title' => $data['title'],
            'date' => $data['date'],
            'max_participants' => $data['max_participants'],
            'status' => EventStatus::Registration,
            'responsible_id' => $responsible->id,
        ]);
    }
    public function update(int $id, array $data): void{
        $event= $this->getById($id);

        $currentLogo = $event->logo_public_id;

        $event->update($data);

        $deletedFiles = request()->input('deleted_files', []);
        foreach ($deletedFiles as $fileId) {
            $file = File::find($fileId);
            if ($file) {
                CloudinaryService::delete($file->public_id);
                $file->delete();
            }
        }

        if (request()->hasFile('logo')) {
            if ($currentLogo) {
                CloudinaryService::delete($currentLogo);
            }
			UploadFiles::execute($event, 'logo');
		} else if(request()->input('deleteLogo') === 'true'){
            Log::info("Logo eliminar");
            Log::info($currentLogo);
            if ($currentLogo) {
                CloudinaryService::delete($currentLogo);
                $event->logo_url = null;
                $event->logo_public_id = null;
                $event->save();
            }
        }

        if (request()->hasFile('documents')) {
			UploadFiles::execute($event, 'documents');
		}
    }

    public function delete(int $id): void{
        $this->getById($id)->delete();
    }

    public function addFileToEvent(int $event_id, array $fileData): void{}
    public function getFilesByEvent(int $event_id): void{}

    public function deleteFileFromEvent(int $event_id): void{}
}
