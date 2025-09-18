<?php

namespace App\Http\Controllers;

use App\Http\Requests\DependencyStoreRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\DependencyService;

class TaskDependenciesController extends Controller
{
    protected $dependencyService;

    public function __construct(DependencyService $dependencyService)
    {
        $this->dependencyService = $dependencyService;
    }

    /**
     * Display a listing of the task dependencies.
     */
    public function index(Task $task)
    {
        $this->authorize('view', $task);

        $dependencies = $task->dependencies()->with(['assignee'])->get();

        return TaskResource::collection($dependencies);
    }

    /**
     * Store a newly created dependency in storage.
     */
    public function store(DependencyStoreRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        /** @var Task $dependsOnTask */
        $dependsOnTask = Task::query()->findOrFail($request->input('depends_on_task_id'));

        $this->dependencyService->addDependency($task, $dependsOnTask);

        return response()->json(['message' => 'Dependency added successfully']);
    }

    /**
     * Remove the specified dependency from storage.
     */
    public function destroy(Task $task, Task $dependsOnTask)
    {
        $this->authorize('update', $task);

        $this->dependencyService->removeDependency($task, $dependsOnTask);

        return response()->json(['message' => 'Dependency removed successfully']);
    }
}
