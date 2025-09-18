<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        // Managers can view all tasks
        if ($user->hasRole('manager')) {
            return true;
        }

        // Users can only view tasks assigned to them
        return $task->assignee_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only managers can create tasks
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        // Only managers can update tasks
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Only managers can delete tasks
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        // Only managers can restore tasks
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        // Only managers can force delete tasks
        return $user->hasRole('manager');
    }

    /**
     * Determine whether the user can update the task status.
     */
    public function updateStatus(User $user, Task $task): bool
    {
        // Managers can update status of any task
        if ($user->hasRole('manager')) {
            return true;
        }

        // Users can update status of tasks assigned to them
        return $task->assignee_id === $user->id;
    }
}
