<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatusUpdateRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // This route is replaced by the search endpoint
        return response()->json(['message' => 'Use POST /api/tasks/search instead'], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth('api')->id();
        $data['updated_by'] = auth('api')->id();

        /** @var Task $task */
        $task = Task::query()->create($data);

        return new TaskResource($task->load(['assignee', 'dependencies']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return new TaskResource($task->load(['assignee', 'dependencies']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $data = $request->validated();
        $data['updated_by'] = auth('api')->id();

        $task->update($data);

        return new TaskResource($task->load(['assignee', 'dependencies']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }

    /**
     * Update the status of the specified task.
     */
    public function updateStatus(TaskStatusUpdateRequest $request, Task $task)
    {
        $this->authorize('updateStatus', $task);

        $task->update(['status' => $request->input('status')]);

        return new TaskResource($task->load(['assignee', 'dependencies']));
    }
}