<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersAndTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a manager user
        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => bcrypt('password'),
        ]);
        $manager->assignRole('manager');

        // Create regular users
        $user1 = User::create([
            'name' => 'Regular User 1',
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);
        $user1->assignRole('user');

        $user2 = User::create([
            'name' => 'Regular User 2',
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);
        $user2->assignRole('user');

        // Create sample tasks
        $task1 = Task::create([
            'title' => 'Complete project documentation',
            'description' => 'Write comprehensive documentation for the project',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(7),
            'assignee_id' => $user1->id,
            'created_by' => $manager->id,
            'updated_by' => $manager->id,
        ]);

        $task2 = Task::create([
            'title' => 'Implement user authentication',
            'description' => 'Add JWT authentication to the API',
            'status' => 'completed',
            'due_date' => Carbon::now()->subDays(2),
            'assignee_id' => $user2->id,
            'created_by' => $manager->id,
            'updated_by' => $manager->id,
        ]);

        $task3 = Task::create([
            'title' => 'Design database schema',
            'description' => 'Create ERD and database schema for the application',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(3),
            'assignee_id' => $user1->id,
            'created_by' => $manager->id,
            'updated_by' => $manager->id,
        ]);

        $task4 = Task::create([
            'title' => 'Setup CI/CD pipeline',
            'description' => 'Configure continuous integration and deployment',
            'status' => 'canceled',
            'due_date' => Carbon::now()->addDays(10),
            'assignee_id' => $user2->id,
            'created_by' => $manager->id,
            'updated_by' => $manager->id,
        ]);

        $task5 = Task::create([
            'title' => 'Write unit tests',
            'description' => 'Create comprehensive test coverage for all features',
            'status' => 'pending',
            'due_date' => Carbon::now()->addDays(5),
            'assignee_id' => $user1->id,
            'created_by' => $manager->id,
            'updated_by' => $manager->id,
        ]);

        // Create task dependencies (task1 depends on task2)
        $task1->dependencies()->attach($task2->id);

        echo "Demo users created:\n";
        echo "Manager: manager@example.com / password\n";
        echo "User 1: user1@example.com / password\n";
        echo "User 2: user2@example.com / password\n";
    }
}
