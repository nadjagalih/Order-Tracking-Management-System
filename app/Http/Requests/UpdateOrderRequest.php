<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'service_type' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:draft,in_progress,review,revision,revision_1,revision_2,completed,cancelled',
            'estimated_completion' => 'required|date',
            'actual_completion' => 'nullable|date',
            'notes' => 'nullable|string',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'client_name' => 'nama klien',
            'client_email' => 'email klien',
            'client_phone' => 'telepon klien',
            'service_type' => 'layanan',
            'description' => 'deskripsi',
            'status' => 'status',
            'estimated_completion' => 'estimasi selesai',
            'actual_completion' => 'tanggal selesai',
            'notes' => 'catatan',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.in' => 'Status harus salah satu dari: pending, in_progress, completed, cancelled.',
        ];
    }
}
