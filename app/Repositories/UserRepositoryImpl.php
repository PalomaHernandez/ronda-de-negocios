<?php
namespace App\Repositories;

use App\Repositories\Interfaces\UserRepository;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepositoryImpl implements UserRepository {

	public function authenticated():?User{
		return request()->user();
	}

	public function findById(int $id):User{
		return User::findOrFail($id);
	}

    public function findByEmail(string $email):User{
		return User::where('email', $email)->firstOrFail();
	}

	public function create():User{
		$validatedData = request()->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'nullable|string',
            'activity' => 'nullable|string',
            'location' => 'nullable|string',
            'website' => 'nullable|url',
            'logo_path' => 'nullable|string',
        ]);
		//Asignar rol?
        $user = User::create($validatedData); 

        return response()->json($user, 201); 
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

		return $user; 
	}

	//Validación para creación de usuario participante.
	private function validateParticipant():array{
		return request()->validate([
			 'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required|string',
            'activity' => 'nullable|string',
            'location' => 'nullable|string',
            'website' => 'nullable|url',
            'logo_path' => 'nullable|string',
		]);
	}

	public function update(int $id):User{
		$user = $this->findById($id);
		//$user->update($this->validate());
		return $user->fresh();
	}

	public function destroy(int $id):?bool{
		return $this->findById($id)->delete();
	}

    public function addImage(int $participant_id, array $imageData):void{
        
    }

    public function getImages(int $participant_id):Collection|array{
        return [];
    }

    public function deleteImage(int $image_id): void{
        
    }

}