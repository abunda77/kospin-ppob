<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Role Management
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'roles.manage',

            // Permission Management
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',
            'permissions.manage',

            // Dashboard
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Administrator (Super Admin) - has all permissions
        $adminRole = \Spatie\Permission\Models\Role::create(['name' => 'Administrator']);
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // Operator - limited permissions
        $operatorRole = \Spatie\Permission\Models\Role::create(['name' => 'Operator']);
        $operatorRole->givePermissionTo([
            'dashboard.view',
            'users.view',
        ]);

        // Guest - minimal permissions
        $guestRole = \Spatie\Permission\Models\Role::create(['name' => 'Guest']);
        $guestRole->givePermissionTo([
            'dashboard.view',
        ]);

        // Create default admin user
        $admin = \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@kospin-ppob.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Administrator');

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
