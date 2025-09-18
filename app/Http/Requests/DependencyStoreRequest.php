<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;

class DependencyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole('manager');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'depends_on_task_id' => 'required|exists:tasks,id',
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
            // Get the task ID from the route parameter (could be Task object or ID)
            $taskId = $this->route('task');
            if ($taskId instanceof Task) {
                $taskId = $taskId->id;
            }
            
            // Get the dependency task ID from input
            $dependsOnTaskId = $this->input('depends_on_task_id');

            // Reject self-dependency
            if ($taskId == $dependsOnTaskId) {
                $validator->errors()->add('depends_on_task_id', 'A task cannot depend on itself.');
            }

            // Check if dependency already exists
            /** @var Task|null $task */
            $task = Task::query()->find($taskId);
            if ($task && $task->dependencies()->where('depends_on_task_id', $dependsOnTaskId)->exists()) {
                $validator->errors()->add('depends_on_task_id', 'This dependency already exists.');
            }

            // Check for cycles (simplified version - in a real app, you'd implement a more robust cycle detection)
            /** @var Task|null $dependsOnTask */
            $dependsOnTask = Task::query()->find($dependsOnTaskId);

            if ($task && $dependsOnTask) {
                // Check if the dependency task already depends on this task (direct cycle)
                if ($dependsOnTask->dependencies()->where('task_id', $taskId)->exists()) {
                    $validator->errors()->add('depends_on_task_id', 'Adding this dependency would create a cycle.');
                }
            }
        });
    }
}