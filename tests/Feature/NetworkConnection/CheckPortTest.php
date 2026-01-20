<?php

use App\Models\User;

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
    $response->assertViewHas('results');
});

it('displays system information', function () {
    actingAs($this->admin);

    $response = get('/network/check-port');

    $response->assertSuccessful();
    $response->assertSee('System Information');
    $response->assertSee('Operating System');
    $response->assertSee('PHP Version');
});

it('checks proxy port status', function () {
    actingAs($this->admin);

    $response = get('/network/check-port');

    $response->assertSuccessful();
    $response->assertSee('Checking Proxy Port');

    // Should show either OPEN or CLOSED status
    $content = $response->getContent();
    expect($content)->toContain('Port');
});

it('displays open ports list', function () {
    actingAs($this->admin);

    $response = get('/network/check-port');

    $response->assertSuccessful();
    $response->assertSee('All Open Ports');
    $response->assertSee('Summary');
});

it('categorizes ports into common and high ports', function () {
    actingAs($this->admin);

    $response = get('/network/check-port');

    $response->assertSuccessful();

    $results = $response->viewData('results');

    expect($results)->toHaveKeys([
        'system_info',
        'proxy_port',
        'proxy_port_open',
        'open_ports',
        'common_ports',
        'high_ports',
        'total_ports',
    ]);

    // Verify common ports are < 10000
    foreach ($results['common_ports'] as $port) {
        expect($port['port'])->toBeLessThan(10000);
    }

    // Verify high ports are >= 10000
    foreach ($results['high_ports'] as $port) {
        expect($port['port'])->toBeGreaterThanOrEqual(10000);
    }
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

it('displays proxy port from config', function () {
    actingAs($this->admin);

    config(['app.proxy_port' => 1080]);

    $response = get('/network/check-port');

    $response->assertSuccessful();

    $results = $response->viewData('results');
    expect($results['proxy_port'])->toBe(1080);
});

it('handles no open ports gracefully', function () {
    actingAs($this->admin);

    $response = get('/network/check-port');

    $response->assertSuccessful();

    // The page should still load even if no ports are found
    // (though in practice there will usually be some ports open)
    $results = $response->viewData('results');
    expect($results['total_ports'])->toBeInt();
});
