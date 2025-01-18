<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepository;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller{
    public function __construct(private readonly UserRepository $repository){}

    public function index()
    {
        $user = auth()->user();
		if(request()->expectsJson()){
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
        $user = $this->repository->create();
        if (!$user) {
            return response()->json(['message' => 'Registro no exitoso'], 400);
        }
		return response()->json($user, 201);
 
    }

    
    public function update(int $id)
    {
        $user = $this->repository->update($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404); 
        }

        return response()->json($user); 
    }

    public function destroy($id)
    {
        $isDeleted =$this->repository->destroy($id);

        if (!$isDeleted) {
            return response()->json(['message' => 'User could not be deleted'], 404); 
        }

        return response()->json(['message' => 'User deleted successfully'], 200); 
    }
}
