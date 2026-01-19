<?php

use App\Livewire\PelangganCrud;
use App\Models\Pelanggan;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Administrator');
});

it('requires authentication to access pelanggan page', function () {
    $response = $this->get(route('pelanggan.index'));

    $response->assertRedirect(route('login'));
});

it('displays pelanggan page for authenticated user', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('pelanggan.index'));

    $response->assertOk();
    $response->assertSeeLivewire(PelangganCrud::class);
});

it('can search pelanggan by name', function () {
    $this->actingAs($this->user);

    Pelanggan::factory()->create(['nama' => 'John Doe', 'no_hp' => '081234567890']);
    Pelanggan::factory()->create(['nama' => 'Jane Smith', 'no_hp' => '081234567891']);

    Livewire::test(PelangganCrud::class)
        ->set('search', 'John')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

it('can search pelanggan by phone number', function () {
    $this->actingAs($this->user);

    Pelanggan::factory()->create(['nama' => 'John Doe', 'no_hp' => '081234567890']);
    Pelanggan::factory()->create(['nama' => 'Jane Smith', 'no_hp' => '089876543210']);

    Livewire::test(PelangganCrud::class)
        ->set('search', '08987')
        ->assertSee('Jane Smith')
        ->assertDontSee('John Doe');
});

it('can search pelanggan by email', function () {
    $this->actingAs($this->user);

    Pelanggan::factory()->create(['nama' => 'John Doe', 'email' => 'john@example.com', 'no_hp' => '081234567890']);
    Pelanggan::factory()->create(['nama' => 'Jane Smith', 'email' => 'jane@example.com', 'no_hp' => '081234567891']);

    Livewire::test(PelangganCrud::class)
        ->set('search', 'john@')
        ->assertSee('John Doe')
        ->assertDontSee('Jane Smith');
});

it('can open create modal', function () {
    $this->actingAs($this->user);

    Livewire::test(PelangganCrud::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->assertSet('isEditing', false);
});

it('can create pelanggan with required fields only', function () {
    $this->actingAs($this->user);

    Livewire::test(PelangganCrud::class)
        ->set('nama', 'Test Customer')
        ->set('no_hp', '081234567890')
        ->call('save');

    $this->assertDatabaseHas('pelanggan', [
        'nama' => 'Test Customer',
        'no_hp' => '081234567890',
    ]);
});

it('can create pelanggan with all fields', function () {
    $this->actingAs($this->user);

    Livewire::test(PelangganCrud::class)
        ->set('nama', 'Complete Customer')
        ->set('email', 'customer@example.com')
        ->set('no_hp', '081234567890')
        ->set('alamat', 'Jl. Test No. 123')
        ->set('kota', 'Jakarta')
        ->set('provinsi', 'DKI Jakarta')
        ->set('kode_pos', '12345')
        ->set('aktif', true)
        ->set('catatan', 'Test notes')
        ->call('save');

    $this->assertDatabaseHas('pelanggan', [
        'nama' => 'Complete Customer',
        'email' => 'customer@example.com',
        'no_hp' => '081234567890',
        'alamat' => 'Jl. Test No. 123',
        'kota' => 'Jakarta',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '12345',
        'aktif' => true,
        'catatan' => 'Test notes',
    ]);
});

it('validates required fields on create', function () {
    $this->actingAs($this->user);

    Livewire::test(PelangganCrud::class)
        ->set('nama', '')
        ->set('no_hp', '')
        ->call('save')
        ->assertHasErrors(['nama', 'no_hp']);
});

it('validates email format', function () {
    $this->actingAs($this->user);

    Livewire::test(PelangganCrud::class)
        ->set('nama', 'Test Customer')
        ->set('email', 'invalid-email')
        ->set('no_hp', '081234567890')
        ->call('save')
        ->assertHasErrors(['email']);
});

it('validates unique email on create', function () {
    $this->actingAs($this->user);

    Pelanggan::factory()->create(['email' => 'existing@example.com', 'no_hp' => '081234567890']);

    Livewire::test(PelangganCrud::class)
        ->set('nama', 'Test Customer')
        ->set('email', 'existing@example.com')
        ->set('no_hp', '089876543210')
        ->call('save')
        ->assertHasErrors(['email']);
});

it('validates unique phone number on create', function () {
    $this->actingAs($this->user);

    Pelanggan::factory()->create(['no_hp' => '081234567890']);

    Livewire::test(PelangganCrud::class)
        ->set('nama', 'Test Customer')
        ->set('no_hp', '081234567890')
        ->call('save')
        ->assertHasErrors(['no_hp']);
});

it('can open edit modal', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create();

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan->id)
        ->assertSet('showModal', true)
        ->assertSet('isEditing', true)
        ->assertSet('nama', $pelanggan->nama)
        ->assertSet('no_hp', $pelanggan->no_hp);
});

it('can update pelanggan', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create([
        'nama' => 'Old Name',
        'no_hp' => '081234567890',
    ]);

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan->id)
        ->set('nama', 'New Name')
        ->set('no_hp', '089876543210')
        ->call('save');

    $this->assertDatabaseHas('pelanggan', [
        'id' => $pelanggan->id,
        'nama' => 'New Name',
        'no_hp' => '089876543210',
    ]);
});

it('can update pelanggan with same email', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create([
        'nama' => 'Test Customer',
        'email' => 'test@example.com',
        'no_hp' => '081234567890',
    ]);

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan->id)
        ->set('nama', 'Updated Name')
        ->set('email', 'test@example.com') // Same email
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('pelanggan', [
        'id' => $pelanggan->id,
        'nama' => 'Updated Name',
        'email' => 'test@example.com',
    ]);
});

it('can update pelanggan with same phone number', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create([
        'nama' => 'Test Customer',
        'no_hp' => '081234567890',
    ]);

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan->id)
        ->set('nama', 'Updated Name')
        ->set('no_hp', '081234567890') // Same phone
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('pelanggan', [
        'id' => $pelanggan->id,
        'nama' => 'Updated Name',
        'no_hp' => '081234567890',
    ]);
});

it('validates unique email on update for other pelanggan', function () {
    $this->actingAs($this->user);

    $pelanggan1 = Pelanggan::factory()->create(['email' => 'existing@example.com', 'no_hp' => '081234567890']);
    $pelanggan2 = Pelanggan::factory()->create(['email' => 'another@example.com', 'no_hp' => '089876543210']);

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan2->id)
        ->set('email', 'existing@example.com')
        ->call('save')
        ->assertHasErrors(['email']);
});

it('validates unique phone number on update for other pelanggan', function () {
    $this->actingAs($this->user);

    $pelanggan1 = Pelanggan::factory()->create(['no_hp' => '081234567890']);
    $pelanggan2 = Pelanggan::factory()->create(['no_hp' => '089876543210']);

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan2->id)
        ->set('no_hp', '081234567890')
        ->call('save')
        ->assertHasErrors(['no_hp']);
});

it('can handle null values in optional fields during edit', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create([
        'nama' => 'Test Customer',
        'email' => null,
        'no_hp' => '081234567890',
        'alamat' => null,
        'kota' => null,
        'provinsi' => null,
        'kode_pos' => null,
        'catatan' => null,
    ]);

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan->id)
        ->assertSet('nama', 'Test Customer')
        ->assertSet('no_hp', '081234567890')
        ->assertHasNoErrors();
});

it('can open delete confirmation modal', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create();

    Livewire::test(PelangganCrud::class)
        ->call('confirmDelete', $pelanggan->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('pelangganId', $pelanggan->id);
});

it('can delete pelanggan', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create();

    Livewire::test(PelangganCrud::class)
        ->call('confirmDelete', $pelanggan->id)
        ->call('delete');

    $this->assertDatabaseMissing('pelanggan', [
        'id' => $pelanggan->id,
    ]);
});

it('can toggle pelanggan status', function () {
    $this->actingAs($this->user);

    $pelanggan = Pelanggan::factory()->create(['aktif' => true]);

    Livewire::test(PelangganCrud::class)
        ->call('edit', $pelanggan->id)
        ->set('aktif', false)
        ->call('save');

    $this->assertDatabaseHas('pelanggan', [
        'id' => $pelanggan->id,
        'aktif' => false,
    ]);
});

it('can close modal and reset form', function () {
    $this->actingAs($this->user);

    Livewire::test(PelangganCrud::class)
        ->set('nama', 'Test')
        ->set('no_hp', '081234567890')
        ->call('closeModal')
        ->assertSet('showModal', false)
        ->assertSet('nama', '')
        ->assertSet('no_hp', '');
});

it('paginates pelanggan results', function () {
    $this->actingAs($this->user);

    Pelanggan::factory()->count(15)->create();

    Livewire::test(PelangganCrud::class)
        ->assertSet('perPage', 10)
        ->assertSee('Showing');
});

it('can change per page value', function () {
    $this->actingAs($this->user);

    Pelanggan::factory()->count(15)->create();

    Livewire::test(PelangganCrud::class)
        ->set('perPage', 20)
        ->assertSet('perPage', 20);
});
