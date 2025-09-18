<?php

namespace App\Observers;

use App\Models\Task;
use App\Support\TaskCache;

class TaskObserver
{
    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        TaskCache::bump();
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        TaskCache::bump();
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        TaskCache::bump();
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        TaskCache::bump();
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        TaskCache::bump();
    }
}
