<?php

use App\Livewire\SubKategoriCrud;
use App\Models\Kategori;
use App\Models\SubKategori;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Administrator');
    $this->kategori = Kategori::factory()->create(['nama' => 'Pulsa', 'kode' => 'PULSA']);
});

it('requires authentication to access sub kategori page', function () {
    $response = $this->get(route('sub-kategori.index'));

    $response->assertRedirect(route('login'));
});

it('displays sub kategori page for authenticated admin', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('sub-kategori.index'));

    $response->assertOk();
    $response->assertSeeLivewire(SubKategoriCrud::class);
});

it('can search sub kategori', function () {
    $this->actingAs($this->user);

    SubKategori::factory()->create(['nama' => 'Telkomsel', 'kode' => 'TSEL', 'kategori_id' => $this->kategori->id]);
    SubKategori::factory()->create(['nama' => 'Indosat', 'kode' => 'ISAT', 'kategori_id' => $this->kategori->id]);

    Livewire::test(SubKategoriCrud::class)
        ->set('search', 'Telkomsel')
        ->assertSee('Telkomsel')
        ->assertDontSee('Indosat');
});

it('can filter by kategori', function () {
    $this->actingAs($this->user);

    $kategori2 = Kategori::factory()->create(['nama' => 'PPOB', 'kode' => 'PPOB']);

    SubKategori::factory()->create(['nama' => 'Telkomsel', 'kode' => 'TSEL', 'kategori_id' => $this->kategori->id]);
    SubKategori::factory()->create(['nama' => 'PLN', 'kode' => 'PLN', 'kategori_id' => $kategori2->id]);

    Livewire::test(SubKategoriCrud::class)
        ->set('filterKategori', $this->kategori->id)
        ->assertSee('Telkomsel')
        ->assertDontSee('PLN');
});

it('can open create modal', function () {
    $this->actingAs($this->user);

    Livewire::test(SubKategoriCrud::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->assertSet('isEditing', false);
});

it('can create sub kategori', function () {
    $this->actingAs($this->user);

    Livewire::test(SubKategoriCrud::class)
        ->set('kategoriId', $this->kategori->id)
        ->set('nama', 'Test Sub Kategori')
        ->set('kode', 'TESTSUB001')
        ->set('deskripsi', 'Deskripsi test')
        ->set('aktif', true)
        ->set('urutan', 1)
        ->call('save');

    $this->assertDatabaseHas('sub_kategori', [
        'nama' => 'Test Sub Kategori',
        'kode' => 'TESTSUB001',
        'kategori_id' => $this->kategori->id,
    ]);
});

it('validates required fields on create', function () {
    $this->actingAs($this->user);

    Livewire::test(SubKategoriCrud::class)
        ->set('kategoriId', null)
        ->set('nama', '')
        ->set('kode', '')
        ->call('save')
        ->assertHasErrors(['kategoriId', 'nama', 'kode']);
});

it('can open edit modal', function () {
    $this->actingAs($this->user);

    $subKategori = SubKategori::factory()->create(['kategori_id' => $this->kategori->id]);

    Livewire::test(SubKategoriCrud::class)
        ->call('edit', $subKategori->id)
        ->assertSet('showModal', true)
        ->assertSet('isEditing', true)
        ->assertSet('nama', $subKategori->nama);
});

it('can update sub kategori', function () {
    $this->actingAs($this->user);

    $subKategori = SubKategori::factory()->create([
        'nama' => 'Old Name',
        'kode' => 'OLD',
        'kategori_id' => $this->kategori->id,
    ]);

    Livewire::test(SubKategoriCrud::class)
        ->call('edit', $subKategori->id)
        ->set('nama', 'New Name')
        ->set('kode', 'NEW')
        ->call('save');

    $this->assertDatabaseHas('sub_kategori', [
        'id' => $subKategori->id,
        'nama' => 'New Name',
        'kode' => 'NEW',
    ]);
});

it('can open delete confirmation modal', function () {
    $this->actingAs($this->user);

    $subKategori = SubKategori::factory()->create(['kategori_id' => $this->kategori->id]);

    Livewire::test(SubKategoriCrud::class)
        ->call('confirmDelete', $subKategori->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('subKategoriId', $subKategori->id);
});

it('can delete sub kategori', function () {
    $this->actingAs($this->user);

    $subKategori = SubKategori::factory()->create(['kategori_id' => $this->kategori->id]);

    Livewire::test(SubKategoriCrud::class)
        ->call('confirmDelete', $subKategori->id)
        ->call('delete');

    $this->assertDatabaseMissing('sub_kategori', [
        'id' => $subKategori->id,
    ]);
});
