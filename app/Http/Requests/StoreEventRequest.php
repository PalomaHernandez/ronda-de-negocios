<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'title' => 'required|string|max:255|unique:events,title',
            'date' => 'required|date',
            'max_participants' => 'required|integer|min:1',
            'responsible_email' => 'required|email',
            'responsible_password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'El título del evento es obligatorio',
            'title.unique' => 'El título del evento ya está en uso',
            'date.required' => 'La fecha del evento es obligatoria',
            'responsible_email.required' => 'El mail del responsable es obligatorio',
            'responsible_password.required' => 'La contraseña del responsable es obligatoria',
            'responsible_password.confirmed' => 'La confirmación de la contraseña no coincide',
            'responsible_password.min' => 'La contraseña debe tener mínimo 8 caracteres',
        ];
    }
}
