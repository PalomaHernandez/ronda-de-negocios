<?php

namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepository {

	public function authenticated():?User;

	public function findById(int $id):User;
    public function findByEmail(string $email):User;

	public function create():User;

	public function createOrUpdateResponsible(array $data):User;

	public function update(int $id):User;

	public function destroy(int $id):?bool;

    public function addImage(int $participant_id, array $imageData): void;
    public function getImages(int $participant_id): Collection|array;

    public function deleteImage(int $image_id): void;

}