<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('can login with valid credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'message',
            'user' => ['id', 'name', 'email'],
            'token',
            'token_type',
        ])
        ->assertJson([
            'message' => 'Login successful',
            'token_type' => 'Bearer',
        ]);
});

it('cannot login with invalid credentials', function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertUnauthorized()
        ->assertJson([
            'message' => 'The provided credentials are incorrect.',
        ]);
});

it('requires email and password for login', function () {
    $response = $this->postJson('/api/login', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});

it('requires valid email format for login', function () {
    $response = $this->postJson('/api/login', [
        'email' => 'invalid-email',
        'password' => 'password123',
    ]);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('can get authenticated user info', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->getJson('/api/user');

    $response->assertSuccessful()
        ->assertJson([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
});

it('cannot get user info without authentication', function () {
    $response = $this->getJson('/api/user');

    $response->assertUnauthorized();
});

it('can logout successfully', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/logout');

    $response->assertSuccessful()
        ->assertJson([
            'message' => 'Logged out successfully',
        ]);
});

it('cannot logout without authentication', function () {
    $response = $this->postJson('/api/logout');

    $response->assertUnauthorized();
});

it('creates token with expiration date', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->postJson('/api/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertSuccessful();

    // Verify token exists in database
    expect($user->tokens()->count())->toBe(1);
    expect($user->tokens()->first()->expires_at)->not->toBeNull();
});

it('revokes token on logout', function () {
    $user = User::factory()->create();

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/logout');

    $response->assertSuccessful();

    // Verify token is deleted
    expect($user->tokens()->count())->toBe(0);
});
