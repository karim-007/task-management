<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCommentUpdateRequest extends FormRequest
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
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'task_id' => ['nullable', 'integer', 'exists:tasks,id'],
            'comment' => ['nullable', 'string'],
            'created_by' => ['nullable'],
            'updated_by' => ['nullable'],
            'ip' => ['nullable', 'string', 'max:50'],
            'browser' => ['nullable', 'string'],
        ];
    }
}
