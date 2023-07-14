<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'deadline' => ['required'],
            'assign_at' => ['required'],
            'assign_by' => ['nullable'],
            'assign_to' => ['nullable'],
            'status' => ['required', 'integer'],
            'is_active' => ['required'],
            'created_by' => ['nullable'],
            'updated_by' => ['nullable'],
            'ip' => ['nullable', 'string', 'max:50'],
            'browser' => ['nullable', 'string'],
        ];
    }
}
