<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class TaskStatusUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if user can update task status based on policy
        /** @var Task $task */
        $task = $this->route('task');

        return $this->user()->can('updateStatus', $task);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|in:pending,completed,canceled',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // If status is being set to completed, check dependencies
            if ($this->input('status') === 'completed') {
                /** @var Task $task */
                $task = $this->route('task');
                $uncompletedDependencies = $task->dependencies()->where('status', '!=', 'completed')->get();

                if ($uncompletedDependencies->count() > 0) {
                    $validator->errors()->add('status', 'Cannot complete task while dependencies are not completed.');
                }
            }
        });
    }
}
