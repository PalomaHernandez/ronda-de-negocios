<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'name' => 'required|string|max:255',
            'activity' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'website' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'El email es obligatorio.',
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'activity.required' => 'La actividad es obligatoria.',
            'location.required' => 'La ubicación es obligatoria.',
            'website.url' => 'El sitio web debe ser una URL válida.',
            'logo.image' => 'El logo debe ser una imagen.',
            'gallery.*.image' => 'Cada archivo en la galería debe ser una imagen.',
            'gallery.*.mimes' => 'Cada imagen debe estar en formato JPG o PNG.',
            'gallery.*.max' => 'Cada imagen no puede superar los 2MB.',
        ];
    }
}
