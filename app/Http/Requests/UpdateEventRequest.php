<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
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
            'description' => 'nullable|string',
            'starts_at' => 'nullable|date_format:H:i:s',
            'ends_at' => 'nullable|date_format:H:i:s',
            'date' => 'nullable|date',
            'meeting_duration' => 'nullable|integer|min:1',
            'time_between_meetings' => 'nullable|integer|min:0',
            'inscription_end_date' => 'nullable|date_format:Y-m-d\TH:i',
            'matching_end_date' => 'nullable|date_format:Y-m-d\TH:i',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,txt',
            'tables_needed' => 'nullable|integer',
            'max_participants' => 'nullable|integer',
            'meetings_per_user' => 'nullable|integer',
        ];
    }
}
