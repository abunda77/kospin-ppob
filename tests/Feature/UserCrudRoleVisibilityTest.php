<?php

use App\Livewire\UserCrud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create roles if they don't exist
    Role::firstOrCreate(['name' => 'Administrator']);
    Role::firstOrCreate(['name' => 'Operator']);
    Role::firstOrCreate(['name' => 'Guest']);
});

it('shows all roles to administrator users', function () {
    // Create an Administrator user
    $admin = User::factory()->create();
    $admin->assignRole('Administrator');

    // Act as the Administrator
    $this->actingAs($admin);

    // Render the UserCrud component
    $component = Livewire::test(UserCrud::class)
        ->call('create');

    // Assert that all three roles are available
    expect($component->viewData('roles'))
        ->toHaveCount(3)
        ->and($component->viewData('roles')->pluck('name')->toArray())
        ->toContain('Administrator', 'Operator', 'Guest');
});

it('hides administrator role from operator users', function () {
    // Create an Operator user
    $operator = User::factory()->create();
    $operator->assignRole('Operator');

    // Act as the Operator
    $this->actingAs($operator);

    // Render the UserCrud component
    $component = Livewire::test(UserCrud::class)
        ->call('create');

    // Assert that only Operator and Guest roles are available
    expect($component->viewData('roles'))
        ->toHaveCount(2)
        ->and($component->viewData('roles')->pluck('name')->toArray())
        ->toContain('Operator', 'Guest')
        ->not->toContain('Administrator');
});

it('hides administrator role from guest users', function () {
    // Create a Guest user
    $guest = User::factory()->create();
    $guest->assignRole('Guest');

    // Act as the Guest
    $this->actingAs($guest);

    // Render the UserCrud component
    $component = Livewire::test(UserCrud::class)
        ->call('create');

    // Assert that only Operator and Guest roles are available
    expect($component->viewData('roles'))
        ->toHaveCount(2)
        ->and($component->viewData('roles')->pluck('name')->toArray())
        ->toContain('Operator', 'Guest')
        ->not->toContain('Administrator');
});

it('maintains role filtering when editing users', function () {
    // Create an Operator user
    $operator = User::factory()->create();
    $operator->assignRole('Operator');

    // Create another user to edit
    $userToEdit = User::factory()->create();
    $userToEdit->assignRole('Guest');

    // Act as the Operator
    $this->actingAs($operator);

    // Edit the user
    $component = Livewire::test(UserCrud::class)
        ->call('edit', $userToEdit->id);

    // Assert that only Operator and Guest roles are available
    expect($component->viewData('roles'))
        ->toHaveCount(2)
        ->and($component->viewData('roles')->pluck('name')->toArray())
        ->toContain('Operator', 'Guest')
        ->not->toContain('Administrator');
});
