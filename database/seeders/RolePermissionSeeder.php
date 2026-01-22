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

            // Kategori Management
            'kategori.view',
            'kategori.create',
            'kategori.edit',
            'kategori.delete',

            // Sub Kategori Management
            'sub_kategori.view',
            'sub_kategori.create',
            'sub_kategori.edit',
            'sub_kategori.delete',

            // Produk PPOB Management
            'produk_ppob.view',
            'produk_ppob.create',
            'produk_ppob.edit',
            'produk_ppob.delete',

            // Pelanggan Management
            'pelanggan.view',
            'pelanggan.create',
            'pelanggan.edit',
            'pelanggan.delete',

            // Network Management
            'network.view',

            // Backup Management
            'backup.view',
            'backup.create',
            'backup.delete',

            // Activity Log Management
            'activity_log.view',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Administrator (Super Admin) - has all permissions
        $adminRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrator']);
        $adminRole->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        // Operator - limited permissions
        $operatorRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Operator']);
        $operatorRole->givePermissionTo([
            'dashboard.view',
            // 'users.view',
        ]);

        // Guest - minimal permissions
        $guestRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Guest']);
        $guestRole->givePermissionTo([
            'dashboard.view',
        ]);

        // Create default admin user
        $admin = \App\Models\User::firstOrCreate(
            ['email' => 'admin@kospin-ppob.com'],
            [
                'name' => 'Administrator',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('Administrator');

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
