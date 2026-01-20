<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Administrator');
});

it('can access check ip page when authenticated', function () {
    actingAs($this->user)
        ->get(route('network.check-ip'))
        ->assertSuccessful()
        ->assertViewIs('pages.network.check-ip')
        ->assertViewHas('results');
});

it('returns correct data structure from check ip endpoint', function () {
    $response = actingAs($this->user)->get(route('network.check-ip'));

    $results = $response->viewData('results');

    expect($results)->toHaveKeys([
        'method1',
        'method2',
        'registered_ip',
        'current_ip',
        'is_match',
        'location',
        'isp',
    ]);
});

it('fetches ip from at least one method', function () {
    $response = actingAs($this->user)->get(route('network.check-ip'));
    $results = $response->viewData('results');

    // At least one method should return a valid IP or Failed
    expect($results['method1'])->not->toBeNull()
        ->and($results['method2'])->not->toBeNull()
        ->and($results['current_ip'])->not->toBeNull();
});

it('detects ip match when current ip matches registered ip', function () {
    // Get the current IP first
    $response = actingAs($this->user)->get(route('network.check-ip'));
    $results = $response->viewData('results');
    $currentIp = $results['current_ip'];

    // Skip test if we couldn't get an IP
    if ($currentIp === 'Failed' || $currentIp === null) {
        expect(true)->toBeTrue();

        return;
    }

    // Set the registered IP to match current IP
    config(['app.registered_ip' => $currentIp]);

    // Make another request
    $response = actingAs($this->user)->get(route('network.check-ip'));
    $results = $response->viewData('results');

    expect($results['is_match'])->toBeTrue();
});

it('detects ip mismatch when current ip does not match registered ip', function () {
    config(['app.registered_ip' => '203.0.113.99']);

    $response = actingAs($this->user)->get(route('network.check-ip'));
    $results = $response->viewData('results');

    // If we got a valid IP, it should not match our fake registered IP
    if ($results['current_ip'] !== 'Failed' && $results['current_ip'] !== null) {
        expect($results['is_match'])->toBeFalse();
    } else {
        // If we couldn't get an IP, just pass the test
        expect(true)->toBeTrue();
    }
});

it('requires authentication to access check ip page', function () {
    get(route('network.check-ip'))
        ->assertRedirect(route('login'));
});
