<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        Permission::create(['name' => 'view tasks']);
        Permission::create(['name' => 'create tasks']);
        Permission::create(['name' => 'update tasks']);
        Permission::create(['name' => 'delete tasks']);
        Permission::create(['name' => 'assign tasks']);
        Permission::create(['name' => 'update task status']);

        // Create roles and assign permissions
        $managerRole = Role::create(['name' => 'manager']);
        $managerRole->givePermissionTo([
            'view tasks',
            'create tasks',
            'update tasks',
            'delete tasks',
            'assign tasks',
            'update task status',
        ]);

        $userRole = Role::create(['name' => 'user']);
        $userRole->givePermissionTo([
            'view tasks',
            'update task status',
        ]);
    }
}
