<?php

use App\Livewire\RoleManagement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create permissions
    Permission::create(['name' => 'users.view']);
    Permission::create(['name' => 'users.create']);
    Permission::create(['name' => 'users.edit']);
    Permission::create(['name' => 'users.delete']);
    Permission::create(['name' => 'roles.manage']);

    // Create roles
    $adminRole = Role::create(['name' => 'Administrator']);
    $adminRole->givePermissionTo(Permission::all());

    Role::create(['name' => 'Operator']);
    Role::create(['name' => 'Guest']);

    // Create admin user
    $this->admin = User::factory()->create();
    $this->admin->assignRole('Administrator');
});

test('administrator can access role management page', function () {
    $this->actingAs($this->admin)
        ->get(route('roles.index'))
        ->assertSuccessful();
});

test('non-administrator cannot access role management page', function () {
    $user = User::factory()->create();
    $user->assignRole('Guest');

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertForbidden();
});

test('administrator can view roles list', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->assertSee('Administrator')
        ->assertSee('Operator')
        ->assertSee('Guest');
});

test('administrator can create new role', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->set('name', 'Manager')
        ->set('selectedPermissions', ['users.view', 'users.edit'])
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('roles', ['name' => 'Manager']);

    $role = Role::where('name', 'Manager')->first();
    expect($role->hasPermissionTo('users.view'))->toBeTrue();
    expect($role->hasPermissionTo('users.edit'))->toBeTrue();
});

test('administrator can update existing role', function () {
    $role = Role::create(['name' => 'Moderator']);
    $role->givePermissionTo('users.view');

    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->call('openEditModal', $role->id)
        ->set('name', 'Updated Moderator')
        ->set('selectedPermissions', ['users.view', 'users.create'])
        ->call('save')
        ->assertHasNoErrors();

    $role->refresh();
    expect($role->name)->toBe('Updated Moderator');
    expect($role->hasPermissionTo('users.create'))->toBeTrue();
});

test('administrator can delete custom role', function () {
    $role = Role::create(['name' => 'Custom Role']);

    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->call('confirmDelete', $role->id)
        ->call('delete')
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('roles', ['name' => 'Custom Role']);
});

test('administrator cannot delete protected system roles', function () {
    $adminRole = Role::where('name', 'Administrator')->first();

    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->call('confirmDelete', $adminRole->id)
        ->call('delete');

    $this->assertDatabaseHas('roles', ['name' => 'Administrator']);
});

test('role creation validates required name', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->set('name', '')
        ->call('save')
        ->assertHasErrors(['name']);
});

test('role creation validates unique name', function () {
    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->set('name', 'Administrator')
        ->call('save')
        ->assertHasErrors(['name']);
});

test('search filters roles correctly', function () {
    Role::create(['name' => 'Manager']);
    Role::create(['name' => 'Supervisor']);

    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->set('search', 'Manager')
        ->assertSee('Manager')
        ->assertDontSee('Supervisor');
});

test('role displays correct permission count', function () {
    $role = Role::create(['name' => 'Test Role']);
    $role->givePermissionTo(['users.view', 'users.create', 'users.edit']);

    Livewire::actingAs($this->admin)
        ->test(RoleManagement::class)
        ->assertSee('3 permissions');
});
