<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'nullable|string|max:255',
            'activity' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'gallery' => 'nullable|array',
            'gallery.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'interests' => 'nullable|string|max:255',
            'products_services' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
            'website.url' => 'El sitio web debe ser una URL válida.',
            'logo.image' => 'El logo debe ser una imagen.',
            'gallery.*.image' => 'Cada archivo en la galería debe ser una imagen.',
            'gallery.*.mimes' => 'Cada imagen debe estar en formato JPG o PNG.',
            'gallery.*.max' => 'Cada imagen no puede superar los 2MB.',
        ];
    }
}
