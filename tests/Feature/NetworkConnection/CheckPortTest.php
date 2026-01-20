<?php

use App\Livewire\Network\CheckPort;
use App\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    // Create admin user with network.view permission
    $this->admin = User::factory()->create();
    $this->admin->assignRole('Administrator');
});

it('displays the check port page for authenticated users with permission', function () {
    actingAs($this->admin);

    $response = get('/network/check-port');

    $response->assertSuccessful();
    $response->assertViewIs('pages.network.check-port');
    $response->assertSeeLivewire('network.check-port');
});

it('requires authentication', function () {
    $response = get('/network/check-port');

    $response->assertRedirect('/login');
});

it('requires network.view permission', function () {
    $user = User::factory()->create();
    $user->assignRole('Guest');

    actingAs($user);

    $response = get('/network/check-port');

    $response->assertForbidden();
});

it('can run port check via livewire', function () {
    $component = Livewire::actingAs($this->admin)
        ->test(CheckPort::class);
        
    $component->assertSet('results', null)
        ->assertSet('isRunning', false)
        ->call('runCheck');
        
    $component->assertSet('isRunning', false)
        ->assertNotSet('results', null);
        
    $results = $component->get('results');
    
    expect($results)->toHaveKeys([
        'system_info',
        'proxy_port',
        'proxy_port_open',
        'open_ports',
        'common_ports',
        'high_ports',
        'total_ports',
    ]);
});
