<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'nullable|in:pending,completed,canceled',
            'due_from' => 'nullable|date',
            'due_to' => 'nullable|date|after_or_equal:due_from',
            'assignee_id' => 'nullable|exists:users,id',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }
}
