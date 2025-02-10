<?php
namespace App\Repositories;

use App\Repositories\Interfaces\UserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Actions\UploadImages;

class UserRepositoryImpl implements UserRepository
{

	public function authenticated(): ?User
	{
		return request()->user();
	}

	public function findById(int $id): User
	{
		return User::findOrFail($id);
	}

	public function findByEmail(string $email): User
	{
		return User::where('email', $email)->firstOrFail();
	}

	public function create(array $data): User
	{
		$data['password'] = bcrypt($data['password']); // Encriptamos la contraseña
	
		// Creamos el usuario
		$user = User::create($data);
		
		// Asignamos el rol (si aplica)
		$user->assign('participant');
	
		// Subimos el logo si está presente
		if (request()->hasFile('logo')) {
			UploadImages::execute($user, 'logo');
		}
	
		// Subimos la galería si está presente
		if (request()->hasFile('gallery')) {
			UploadImages::execute($user, 'gallery');
		}
	
		return $user;
	}



	public function createOrUpdateResponsible(array $data): User
	{
		$user = User::firstOrNew(['email' => $data['responsible_email']]);

		if (!$user->exists) {
			$user->password = bcrypt($data['responsible_password']);
			$user->save();
		} else {
			$user->update(['password' => bcrypt($data['responsible_password'])]);
		}
		$user->assign('responsible');
		return $user;
	}

	//Validación para creación de usuario participante.
	private function validateParticipant(): array
	{
		return request()->validate([
			'email' => 'required|email|unique:users,email',
			'password' => 'required|min:8|confirmed',
			'name' => 'required|string',
			'activity' => 'required|string',
			'location' => 'required|string',
			'website' => 'required|url',
			'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
			'gallery' => 'nullable|array',
			'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
		]);
	}

	public function update(int $id): User
	{
		$user = $this->findById($id);
		$validate = request()->validate([
			'name' => 'required|string',
			'activity' => 'required|string',
			'location' => 'required|string',
			'website' => 'required|url',
		]);
		$user->update($validate);
		return $user->fresh();
	}

	public function destroy(int $id): ?bool
	{
		return $this->findById($id)->delete();
	}


}