<?php

use App\Livewire\Network\VerifyEnvironment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Administrator');
});

it('can access verify environment page', function () {
    actingAs($this->user)
        ->get(route('network.verify-environment'))
        ->assertSuccessful()
        ->assertViewIs('pages.network.verify-environment')
        ->assertSeeLivewire('network.verify-environment');
});

it('requires authentication', function () {
    get(route('network.verify-environment'))
        ->assertRedirect(route('login'));
});

it('can run verification via livewire', function () {
    $component = Livewire::actingAs($this->user)
        ->test(VerifyEnvironment::class);

    $component->assertSet('hasRun', false)
        ->assertSet('isRunning', false)
        ->call('runCheck');

    $component->assertSet('hasRun', true)
        ->assertSet('isRunning', false)
        ->assertSet('envFileExists', true); // Assuming .env exists in test env
        
    // Check if variables are populated
    $requiredVars = $component->get('requiredVars');
    expect($requiredVars)->toBeArray();
});
