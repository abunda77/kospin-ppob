<?php

use App\Livewire\KategoriCrud;
use App\Models\Kategori;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Administrator');
});

it('requires authentication to access kategori page', function () {
    $response = $this->get(route('kategori.index'));

    $response->assertRedirect(route('login'));
});

it('displays kategori page for authenticated admin', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('kategori.index'));

    $response->assertOk();
    $response->assertSeeLivewire(KategoriCrud::class);
});

it('can search kategori', function () {
    $this->actingAs($this->user);

    Kategori::factory()->create(['nama' => 'Pulsa', 'kode' => 'PULSA']);
    Kategori::factory()->create(['nama' => 'PPOB', 'kode' => 'PPOB']);

    Livewire::test(KategoriCrud::class)
        ->set('search', 'Pulsa')
        ->assertSee('Pulsa')
        ->assertDontSee('PPOB');
});

it('can open create modal', function () {
    $this->actingAs($this->user);

    Livewire::test(KategoriCrud::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->assertSet('isEditing', false);
});

it('can create kategori', function () {
    $this->actingAs($this->user);

    Livewire::test(KategoriCrud::class)
        ->set('nama', 'Test Kategori')
        ->set('kode', 'TEST001')
        ->set('deskripsi', 'Deskripsi test')
        ->set('aktif', true)
        ->set('urutan', 1)
        ->call('save');

    $this->assertDatabaseHas('kategori', [
        'nama' => 'Test Kategori',
        'kode' => 'TEST001',
    ]);
});

it('validates required fields on create', function () {
    $this->actingAs($this->user);

    Livewire::test(KategoriCrud::class)
        ->set('nama', '')
        ->set('kode', '')
        ->call('save')
        ->assertHasErrors(['nama', 'kode']);
});

it('can open edit modal', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();

    Livewire::test(KategoriCrud::class)
        ->call('edit', $kategori->id)
        ->assertSet('showModal', true)
        ->assertSet('isEditing', true)
        ->assertSet('nama', $kategori->nama);
});

it('can update kategori', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create([
        'nama' => 'Old Name',
        'kode' => 'OLD',
    ]);

    Livewire::test(KategoriCrud::class)
        ->call('edit', $kategori->id)
        ->set('nama', 'New Name')
        ->set('kode', 'NEW')
        ->call('save');

    $this->assertDatabaseHas('kategori', [
        'id' => $kategori->id,
        'nama' => 'New Name',
        'kode' => 'NEW',
    ]);
});

it('can open delete confirmation modal', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();

    Livewire::test(KategoriCrud::class)
        ->call('confirmDelete', $kategori->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('kategoriId', $kategori->id);
});

it('can delete kategori', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();

    Livewire::test(KategoriCrud::class)
        ->call('confirmDelete', $kategori->id)
        ->call('delete');

    $this->assertDatabaseMissing('kategori', [
        'id' => $kategori->id,
    ]);
});
