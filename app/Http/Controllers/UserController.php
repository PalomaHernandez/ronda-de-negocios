<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\Interfaces\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Registration;
use App\Models\Event;
use App\Actions\UploadImages;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function index()
    {
        $user = User::find(auth()->id())->load('images');
        return response()->json($user);
    }


    public function show($id)
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }


    public function store(StoreUserRequest $request)
    {
        try {
            // Validamos los datos
            $validatedData = $request->validated();

            $user = $this->repository->create($validatedData);
            // Generamos el token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Registro exitoso'
            ], 201);

        } catch (ValidationException $e) {
            \Log::error('Error en el registro: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno en el servidor'], 500);
        }
    }

    public function deleteImages(array $images)
    {
        $this->repository->deleteImages($images);
    }

    public function update(UpdateUserRequest $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validatedData = $request->validated();

        $deleted_images = request()->input('deleted_images', []);
        if ($deleted_images) {
            $this->deleteImages($deleted_images);
        }

        if (request()->hasFile('logo')) {
            UploadImages::execute($user instanceof User ? $user : User::find($user->id), 'logo');
        }

        if (request()->hasFile('gallery')) {
            UploadImages::execute($user, 'gallery');
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user->fresh()->load('images')
        ]);
    }

    public function isRegistered($slug)
    {
        $isRegistered = $this->repository->isRegistered($slug);

        return response()->json(['registered' => $isRegistered]);
    }

    public function destroy($id)
    {
        $isDeleted = $this->repository->destroy($id);

        if (!$isDeleted) {
            return response()->json(['message' => 'User could not be deleted'], 404);
        }

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
