# Role & Permission Management

## Overview
Sistem manajemen role dan permission menggunakan **Spatie Laravel Permission** dengan interface CRUD yang dibangun menggunakan **Livewire** dan **Yajra DataTables**.

## Features

### 1. Role Management
- **CRUD Operations**: Create, Read, Update, Delete roles
- **Permission Assignment**: Assign multiple permissions to roles
- **System Role Protection**: Prevent deletion of system roles (Administrator, Operator, Guest)
- **Search & Filter**: Search roles by name
- **DataTables Integration**: Paginated table with sorting

### 2. User Management
- **CRUD Operations**: Create, Read, Update, Delete users
- **Role Assignment**: Assign single role to users
- **Soft Deletes**: Users are soft deleted, not permanently removed
- **Self-Protection**: Users cannot delete their own account
- **Search & Filter**: Search users by name or email
- **DataTables Integration**: Paginated table with sorting

### 3. Authorization
- **Middleware Protection**: Only Administrator role can access management pages
- **Permission-based Access**: UI elements are shown/hidden based on permissions
- **Route Protection**: Routes are protected with `administrator` middleware

## Installation

The system has been installed and configured with the following steps:

1. **Packages Installed**:
   - `spatie/laravel-permission` v6.24
   - `yajra/laravel-datatables-oracle` v12.6

2. **Migrations Run**:
   - Permission tables created
   - Soft deletes added to users table

3. **Seeder Executed**:
   - Roles created: Administrator, Operator, Guest
   - Permissions created for users, roles, and permissions management
   - Default admin user created

## Default Credentials

```
Email: admin@kospin-ppob.com
Password: password
```

**⚠️ IMPORTANT**: Change this password immediately in production!

## Roles & Permissions

### Administrator (Super Admin)
- Has ALL permissions
- Can manage users, roles, and permissions
- Cannot be deleted

### Operator
- `dashboard.view`
- `users.view`

### Guest
- `dashboard.view`

## Available Permissions

### User Management
- `users.view` - View users list
- `users.create` - Create new users
- `users.edit` - Edit existing users
- `users.delete` - Delete users

### Role Management
- `roles.view` - View roles list
- `roles.create` - Create new roles
- `roles.edit` - Edit existing roles
- `roles.delete` - Delete roles
- `roles.manage` - Full role management access

### Permission Management
- `permissions.view` - View permissions list
- `permissions.create` - Create new permissions
- `permissions.edit` - Edit existing permissions
- `permissions.delete` - Delete permissions
- `permissions.manage` - Full permission management access

### Dashboard
- `dashboard.view` - Access dashboard

## Routes

```php
// User Management
GET /users - User management page (Administrator only)

// Role Management
GET /roles - Role management page (Administrator only)
```

## Usage Examples

### Checking Permissions in Blade
```blade
@can('users.create')
    <button>Create User</button>
@endcan
```

### Checking Permissions in Controller
```php
$this->authorize('users.create');
```

### Checking Roles
```php
if ($user->hasRole('Administrator')) {
    // Do something
}
```

### Assigning Role to User
```php
$user->assignRole('Operator');
```

### Giving Permission to Role
```php
$role->givePermissionTo('users.view');
```

## Testing

All features are covered by comprehensive Pest tests:

```bash
# Run all role & permission tests
php artisan test --filter=UserManagement
php artisan test --filter=RoleManagement

# Run all tests
php artisan test
```

### Test Coverage
- ✅ Authorization (Administrator access only)
- ✅ CRUD operations for users
- ✅ CRUD operations for roles
- ✅ Validation rules
- ✅ Permission assignment
- ✅ System role protection
- ✅ Search functionality
- ✅ Soft deletes

## File Structure

```
app/
├── Http/
│   └── Middleware/
│       └── EnsureUserIsAdministrator.php
├── Livewire/
│   ├── UserManagement.php
│   └── RoleManagement.php
└── Models/
    └── User.php (updated with HasRoles trait)

resources/
└── views/
    ├── livewire/
    │   ├── user-management.blade.php
    │   └── role-management.blade.php
    └── pages/
        ├── users/
        │   └── index.blade.php
        └── roles/
            └── index.blade.php

database/
├── migrations/
│   ├── 2026_01_18_044017_create_permission_tables.php
│   └── 2026_01_18_044058_add_soft_deletes_to_users_table.php
└── seeders/
    └── RolePermissionSeeder.php

tests/
└── Feature/
    ├── UserManagementTest.php
    └── RoleManagementTest.php
```

## Security Considerations

1. **Administrator Protection**: Only users with Administrator role can access management pages
2. **Self-Protection**: Users cannot delete their own account
3. **System Role Protection**: System roles (Administrator, Operator, Guest) cannot be deleted
4. **Soft Deletes**: Users are soft deleted, allowing recovery if needed
5. **Password Confirmation**: Required when creating/updating users
6. **Validation**: All inputs are validated before processing

## Customization

### Adding New Permissions

Edit `database/seeders/RolePermissionSeeder.php`:

```php
$permissions = [
    // Add your new permissions here
    'products.view',
    'products.create',
    // ...
];
```

Then run:
```bash
php artisan db:seed --class=RolePermissionSeeder
```

### Adding New Roles

You can add new roles through the UI at `/roles` or programmatically:

```php
$role = Role::create(['name' => 'Manager']);
$role->givePermissionTo(['users.view', 'users.edit']);
```

## Troubleshooting

### Permission Cache Issues
If permissions don't seem to work after changes:
```bash
php artisan permission:cache-reset
```

### Migration Issues
If you need to reset:
```bash
php artisan migrate:fresh --seed
```

## Support

For issues or questions, refer to:
- [Spatie Laravel Permission Documentation](https://spatie.be/docs/laravel-permission/v6)
- [Yajra DataTables Documentation](https://yajrabox.com/docs/laravel-datatables/12.0)
- [Livewire Documentation](https://livewire.laravel.com)
