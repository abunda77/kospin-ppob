<?php

use App\Livewire\UserManagement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles
    Role::create(['name' => 'Administrator']);
    Role::create(['name' => 'Operator']);
    Role::create(['name' => 'Guest']);

    // Create admin user
    $this->admin = User::factory()->create();
    $this->admin->assignRole('Administrator');
});

test('administrator can access user management page', function () {
    $this->actingAs($this->admin)
        ->get(route('users.index'))
        ->assertSuccessful();
});

test('non-administrator cannot access user management page', function () {
    $user = User::factory()->create();
    $user->assignRole('Guest');

    $this->actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
});

test('administrator can view users list', function () {
    $user = User::factory()->create(['name' => 'Test User']);

    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->assertSee('Test User');
});

test('administrator can create new user', function () {
    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->set('name', 'New User')
        ->set('email', 'newuser@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('selectedRole', 'Operator')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'name' => 'New User',
        'email' => 'newuser@example.com',
    ]);

    $user = User::where('email', 'newuser@example.com')->first();
    expect($user->hasRole('Operator'))->toBeTrue();
});

test('administrator can update existing user', function () {
    $user = User::factory()->create(['name' => 'Old Name']);
    $user->assignRole('Guest');

    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->call('openEditModal', $user->id)
        ->set('name', 'Updated Name')
        ->set('selectedRole', 'Operator')
        ->call('save')
        ->assertHasNoErrors();

    $user->refresh();
    expect($user->name)->toBe('Updated Name');
    expect($user->hasRole('Operator'))->toBeTrue();
});

test('administrator can delete user', function () {
    $user = User::factory()->create();

    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->call('confirmDelete', $user->id)
        ->call('delete')
        ->assertHasNoErrors();

    $this->assertSoftDeleted('users', ['id' => $user->id]);
});

test('administrator cannot delete their own account', function () {
    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->call('confirmDelete', $this->admin->id)
        ->call('delete');

    $this->assertDatabaseHas('users', [
        'id' => $this->admin->id,
        'deleted_at' => null,
    ]);
});

test('user creation validates required fields', function () {
    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->set('name', '')
        ->set('email', '')
        ->set('password', '')
        ->set('selectedRole', '')
        ->call('save')
        ->assertHasErrors(['name', 'email', 'password', 'selectedRole']);
});

test('user creation validates email format', function () {
    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->set('email', 'invalid-email')
        ->call('save')
        ->assertHasErrors(['email']);
});

test('user creation validates unique email', function () {
    $existingUser = User::factory()->create(['email' => 'existing@example.com']);

    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->set('name', 'Test User')
        ->set('email', 'existing@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->set('selectedRole', 'Guest')
        ->call('save')
        ->assertHasErrors(['email']);
});

test('user creation validates password confirmation', function () {
    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'different-password')
        ->set('selectedRole', 'Guest')
        ->call('save')
        ->assertHasErrors(['password']);
});

test('search filters users correctly', function () {
    User::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    User::factory()->create(['name' => 'Jane Smith', 'email' => 'jane@example.com']);

    Livewire::actingAs($this->admin)
        ->test(UserManagement::class)
        ->set('search', 'John')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});
