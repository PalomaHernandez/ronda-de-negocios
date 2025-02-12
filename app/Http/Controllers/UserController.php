<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(private readonly UserRepository $repository)
    {
    }

    public function index()
    {
        $user = User::find(auth()->id());
        if (request()->expectsJson()) {
            return response()->json($user);
        }
        return view('users.index', compact('user'));
    }


    public function show($id)
    {
        $user = $this->repository->findById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }


    public function store(Request $request)
    {
        try {
            // Validamos los datos
            $validatedData = $request->validate([
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

            $user = $this->repository->create($validatedData);
            // Generamos el token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Registro exitoso'
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Error en el registro: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno en el servidor'], 500);
        }
    }




    public function update()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validatedData = request()->validate([
            'name' => 'required|string|max:255',
            'activity' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $this->repository->update($user instanceof User ? $user : User::find($user->id), $validatedData);

        return response()->json([
            'message' => 'Perfil actualizado correctamente',
            'user' => $user->fresh()
        ]);
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
