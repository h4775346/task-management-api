<?php

namespace App\Services;

use App\Models\Task;
use App\Support\TaskCache;
use Illuminate\Http\Exceptions\HttpResponseException;

class DependencyService
{
    /**
     * Add a dependency to a task.
     *
     * @throws HttpResponseException
     */
    public function addDependency(Task $task, Task $dependsOn): bool
    {
        // Reject if same id (self-dependency)
        if ($task->id === $dependsOn->id) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'A task cannot depend on itself.',
                ], 422)
            );
        }

        // Cycle detection using DFS
        if ($this->wouldCreateCycle($task, $dependsOn)) {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Adding this dependency would create a cycle.',
                ], 409)
            );
        }

        // Attach relation
        $task->dependencies()->attach($dependsOn->id);

        // Bump cache version
        TaskCache::bump();

        return true;
    }

    /**
     * Remove a dependency from a task.
     */
    public function removeDependency(Task $task, Task $dependsOn): bool
    {
        $task->dependencies()->detach($dependsOn->id);

        // Bump cache version
        TaskCache::bump();

        return true;
    }

    /**
     * Check if a task can be completed (all dependencies are completed).
     */
    public function canComplete(Task $task): bool
    {
        return $task->dependencies()->where('status', '!=', 'completed')->count() === 0;
    }

    /**
     * Detect if adding a dependency would create a cycle using DFS.
     */
    private function wouldCreateCycle(Task $task, Task $dependsOn): bool
    {
        // Simple cycle detection: check if the dependency task already depends on this task
        if ($dependsOn->dependencies()->where('task_id', $task->id)->exists()) {
            return true;
        }

        // More comprehensive cycle detection using DFS
        $visited = [];
        $stack = [$dependsOn->id];

        while (! empty($stack)) {
            $currentId = array_pop($stack);

            // Skip if already visited
            if (in_array($currentId, $visited)) {
                continue;
            }

            // Mark as visited
            $visited[] = $currentId;

            // If we reached the original task, there's a cycle
            if ($currentId == $task->id) {
                return true;
            }

            // Add dependencies to stack
            /** @var Task|null $currentTask */
            $currentTask = Task::query()->find($currentId);
            if ($currentTask) {
                $dependencies = $currentTask->dependencies()->pluck('depends_on_task_id')->toArray();
                $stack = array_merge($stack, $dependencies);
            }
        }

        return false;
    }
}
