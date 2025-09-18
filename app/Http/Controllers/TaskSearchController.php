<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskSearchRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Support\TaskCache;
use Illuminate\Support\Facades\Cache;

class TaskSearchController extends Controller
{
    /**
     * Search and filter tasks.
     */
    public function __invoke(TaskSearchRequest $request)
    {
        $filters = $request->validated();

        // Scope by RBAC
        /** @var User $user */
        $user = auth('api')->user();
        if (! $user->hasRole('manager')) {
            $filters['assignee_id'] = $user->id;
        }

        // Build cache key
        $cacheKey = TaskCache::keyForSearch($user, $filters);
        $cacheTtl = config('cache_ttl.tasks_search');

        // Use cache to retrieve results
        $paginator = Cache::remember($cacheKey, $cacheTtl, function () use ($filters) {
            $query = Task::with(['assignee', 'dependencies']);

            // Apply filters
            if (! empty($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (! empty($filters['due_from'])) {
                $query->where('due_date', '>=', $filters['due_from']);
            }

            if (! empty($filters['due_to'])) {
                $query->where('due_date', '<=', $filters['due_to']);
            }

            if (! empty($filters['assignee_id'])) {
                $query->where('assignee_id', $filters['assignee_id']);
            }

            // Order by due_date and id
            $query->orderBy('due_date', 'asc')->orderBy('id', 'asc');

            // Paginate results
            $perPage = min($filters['per_page'] ?? 15, 100);
            $page = $filters['page'] ?? 1;

            return $query->paginate($perPage, ['*'], 'page', $page);
        });

        return TaskResource::collection($paginator);
    }
}
