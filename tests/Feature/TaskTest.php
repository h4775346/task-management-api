<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected $manager;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolesAndPermissionsSeeder']);

        // Create users
        $this->manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->manager->assignRole('manager');

        $this->user = User::create([
            'name' => 'Regular User',
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);
        $this->user->assignRole('user');
    }

    /**
     * Test that managers can create tasks.
     */
    public function test_managers_can_create_tasks()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'due_date' => '2025-12-31',
            'assignee_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->manager, 'api')
            ->postJson('/api/tasks', $taskData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'assignee_id' => $this->user->id,
        ]);
    }

    /**
     * Test that users cannot create tasks.
     */
    public function test_users_cannot_create_tasks()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'due_date' => '2025-12-31',
            'assignee_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/tasks', $taskData);

        $response->assertStatus(403);
    }

    /**
     * Test task search functionality.
     */
    public function test_task_search_works()
    {
        // Create a task
        $task = Task::create([
            'title' => 'Searchable Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'due_date' => '2025-12-31',
            'assignee_id' => $this->user->id,
            'created_by' => $this->manager->id,
            'updated_by' => $this->manager->id,
        ]);

        $searchData = [
            'status' => 'pending',
            'page' => 1,
            'per_page' => 10,
        ];

        $response = $this->actingAs($this->user, 'api')
            ->postJson('/api/tasks/search', $searchData);

        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Searchable Task']);
    }
}
