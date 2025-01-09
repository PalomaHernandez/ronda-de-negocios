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
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }

    
    public function store(Request $request)
    {
        $user = $this->repository->create();
		if(request()->expectsJson()){
			return response()->json($user);
		}
		return view('users.index', compact('user'));
 
    }

    
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404); 
        }

        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'activity' => 'nullable|string',
            'location' => 'nullable|string',
            'website' => 'nullable|url',
            'logo_path' => 'nullable|string',
        ]);

        $user->update($validatedData); 

        return response()->json($user); 
    }

    public function destroy($id)
    {
        $user = User::find($id); 

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404); 
        }

        $user->delete(); 

        return response()->json(['message' => 'User deleted successfully'], 200); 
    }
}
