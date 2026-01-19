<?php

use App\Livewire\UserCrud;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
});

it('hides super admin from non-administrator users in user list', function () {
    // Create an operator user
    $operator = User::factory()->create();
    $operator->assignRole('Operator');

    // Get the super admin user
    $superAdmin = User::where('email', 'admin@kospin-ppob.com')->first();

    expect($superAdmin)->not->toBeNull();

    // Login as operator
    actingAs($operator);

    // Test the Livewire component
    $component = Livewire::test(UserCrud::class);

    // Get the users from the component's view data
    $users = $component->viewData('users');

    // Super admin should NOT be in the users collection
    expect($users->pluck('id')->contains($superAdmin->id))->toBeFalse();

    // Operator should be in the users collection
    expect($users->pluck('id')->contains($operator->id))->toBeTrue();
});

it('shows super admin to administrator users in user list', function () {
    // Get the super admin user
    $superAdmin = User::where('email', 'admin@kospin-ppob.com')->first();

    expect($superAdmin)->not->toBeNull();

    // Login as super admin (who has Administrator role)
    actingAs($superAdmin);

    // Test the Livewire component
    $component = Livewire::test(UserCrud::class);

    // Get the users from the component's view data
    $users = $component->viewData('users');

    // Super admin SHOULD be in the users collection
    expect($users->pluck('id')->contains($superAdmin->id))->toBeTrue();
});

it('prevents non-administrator from editing super admin', function () {
    // Create an operator user
    $operator = User::factory()->create();
    $operator->assignRole('Operator');

    // Get the super admin user
    $superAdmin = User::where('email', 'admin@kospin-ppob.com')->first();

    expect($superAdmin)->not->toBeNull();

    // Login as operator
    actingAs($operator);

    // Try to edit super admin
    $component = Livewire::test(UserCrud::class)
        ->call('edit', $superAdmin->id);

    // Modal should NOT be shown (permission denied)
    expect($component->get('showModal'))->toBeFalse();
});

it('allows administrator to edit super admin', function () {
    // Get the super admin user
    $superAdmin = User::where('email', 'admin@kospin-ppob.com')->first();

    expect($superAdmin)->not->toBeNull();

    // Login as super admin (who has Administrator role)
    actingAs($superAdmin);

    // Try to edit super admin
    $component = Livewire::test(UserCrud::class)
        ->call('edit', $superAdmin->id);

    // Modal SHOULD be shown
    expect($component->get('showModal'))->toBeTrue();
    expect($component->get('email'))->toBe('admin@kospin-ppob.com');
});

it('prevents non-administrator from deleting super admin', function () {
    // Create an operator user
    $operator = User::factory()->create();
    $operator->assignRole('Operator');

    // Get the super admin user
    $superAdmin = User::where('email', 'admin@kospin-ppob.com')->first();

    expect($superAdmin)->not->toBeNull();

    // Login as operator
    actingAs($operator);

    // Try to delete super admin
    $component = Livewire::test(UserCrud::class)
        ->call('confirmDelete', $superAdmin->id);

    // Delete modal should NOT be shown (permission denied)
    expect($component->get('showDeleteModal'))->toBeFalse();
});

it('allows administrator to delete super admin', function () {
    // Create another administrator user
    $admin = User::factory()->create();
    $admin->assignRole('Administrator');

    // Get the super admin user
    $superAdmin = User::where('email', 'admin@kospin-ppob.com')->first();

    expect($superAdmin)->not->toBeNull();

    // Login as another administrator
    actingAs($admin);

    // Try to delete super admin
    $component = Livewire::test(UserCrud::class)
        ->call('confirmDelete', $superAdmin->id);

    // Delete modal SHOULD be shown
    expect($component->get('showDeleteModal'))->toBeTrue();
});
