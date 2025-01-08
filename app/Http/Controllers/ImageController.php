<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    public function index()
    {
        return response()->json(Image::all());
    }

    public function show($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }
        return response()->json($image);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'path' => 'required|string',
        ]);

        $image = Image::create($validatedData);
        return response()->json($image, 201);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $validatedData = $request->validate([
            'path' => 'required|string',
        ]);

        $image->update($validatedData);
        return response()->json($image);
    }

    public function destroy($id)
    {
        $image = Image::find($id);
        if (!$image) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        $image->delete();
        return response()->json(['message' => 'Image deleted successfully'], 200);
    }
}