<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all(); 
        return response()->json($users); 
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
        $validatedData = $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'name' => 'required|string',
            'activity' => 'nullable|string',
            'location' => 'nullable|string',
            'website' => 'nullable|url',
            'logo_path' => 'nullable|string',
        ]);

        $user = User::create($validatedData); 

        return response()->json($user, 201); 
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
