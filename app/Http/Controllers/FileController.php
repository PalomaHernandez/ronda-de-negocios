<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    public function index()
    {
        $files = File::all();
        return response()->json($files); 
    }

    
    public function show($id)
    {
        $file = File::find($id); 

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404); 
        }

        return response()->json($file);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'path' => 'required|string',
        ]);

        $file = File::create($validatedData); 

        return response()->json($file, 201); 
    }

    public function update(Request $request, $id)
    {
        $file = File::find($id); 

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404); 
        }

        $validatedData = $request->validate([
            'path' => 'required|string',
        ]);

        $file->update($validatedData); 

        return response()->json($file);
    }

    public function destroy($id)
    {
        $file = File::find($id); 

        if (!$file) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $file->delete(); 

        return response()->json(['message' => 'File deleted successfully'], 200);
    }
}
