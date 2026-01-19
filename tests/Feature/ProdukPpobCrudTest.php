<?php

use App\Livewire\ProdukPpobCrud;
use App\Models\Kategori;
use App\Models\ProdukPpob;
use App\Models\SubKategori;
use App\Models\User;
use Livewire\Livewire;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    $this->user = User::factory()->create();
    $this->user->assignRole('Administrator');
});

it('requires authentication to access produk ppob page', function () {
    $response = $this->get(route('produk-ppob.index'));

    $response->assertRedirect(route('login'));
});

it('displays produk ppob page for authenticated admin', function () {
    $this->actingAs($this->user);

    $response = $this->get(route('produk-ppob.index'));

    $response->assertOk();
    $response->assertSeeLivewire(ProdukPpobCrud::class);
});

it('can search produk ppob', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori = SubKategori::factory()->create(['kategori_id' => $kategori->id]);

    ProdukPpob::factory()->create([
        'nama_produk' => 'PLN Prepaid 20.000',
        'kode' => 'PLN20K',
        'sub_kategori_id' => $subKategori->id,
    ]);
    ProdukPpob::factory()->create([
        'nama_produk' => 'TELKOMSEL 10K',
        'kode' => 'TSEL10K',
        'sub_kategori_id' => $subKategori->id,
    ]);

    Livewire::test(ProdukPpobCrud::class)
        ->set('search', 'PLN')
        ->assertSee('PLN Prepaid 20.000')
        ->assertDontSee('TELKOMSEL 10K');
});

it('can filter by sub kategori', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori1 = SubKategori::factory()->create(['kategori_id' => $kategori->id, 'nama' => 'Pulsa']);
    $subKategori2 = SubKategori::factory()->create(['kategori_id' => $kategori->id, 'nama' => 'Token']);

    $produk1 = ProdukPpob::factory()->create([
        'nama_produk' => 'XL 10K',
        'sub_kategori_id' => $subKategori1->id,
    ]);
    $produk2 = ProdukPpob::factory()->create([
        'nama_produk' => 'PLN 20K',
        'sub_kategori_id' => $subKategori2->id,
    ]);

    Livewire::test(ProdukPpobCrud::class)
        ->set('filterSubKategoriId', $subKategori1->id)
        ->assertSee('XL 10K')
        ->assertDontSee('PLN 20K');
});

it('can open create modal', function () {
    $this->actingAs($this->user);

    Livewire::test(ProdukPpobCrud::class)
        ->call('create')
        ->assertSet('showModal', true)
        ->assertSet('isEditing', false);
});

it('can create produk ppob', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori = SubKategori::factory()->create(['kategori_id' => $kategori->id]);

    Livewire::test(ProdukPpobCrud::class)
        ->set('kode', 'PLN5K')
        ->set('nama_produk', 'PLN Prepaid 5.000')
        ->set('sub_kategori_id', $subKategori->id)
        ->set('hpp', 5000)
        ->set('biaya_admin', 1500)
        ->set('fee_mitra', 500)
        ->set('markup', 500)
        ->set('aktif', true)
        ->call('save');

    $this->assertDatabaseHas('produk_ppob', [
        'kode' => 'PLN5K',
        'nama_produk' => 'PLN Prepaid 5.000',
        'sub_kategori_id' => $subKategori->id,
        'hpp' => 5000,
        'biaya_admin' => 1500,
        'fee_mitra' => 500,
        'markup' => 500,
        'harga_beli' => 6500, // 5000 + 1500
        'harga_jual' => 7500, // 6500 + 500 + 500
        'profit' => 1000,     // 7500 - 6500
    ]);
});

it('calculates price fields correctly', function () {
    $this->actingAs($this->user);

    Livewire::test(ProdukPpobCrud::class)
        ->set('hpp', 10000)
        ->assertSet('harga_beli', 10000)
        ->assertSet('harga_jual', 10000)
        ->assertSet('profit', 0)
        
        ->set('biaya_admin', 2000)
        ->assertSet('harga_beli', 12000)
        ->assertSet('harga_jual', 12000)
        ->assertSet('profit', 0)

        ->set('markup', 1000)
        ->assertSet('harga_jual', 13000)
        ->assertSet('profit', 1000)

        ->set('fee_mitra', 500)
        ->assertSet('harga_jual', 13500)
        ->assertSet('profit', 1500);
});

it('validates required fields on create', function () {
    $this->actingAs($this->user);

    Livewire::test(ProdukPpobCrud::class)
        ->set('kode', '')
        ->set('nama_produk', '')
        ->set('sub_kategori_id', null)
        ->call('save')
        ->assertHasErrors(['kode', 'nama_produk', 'sub_kategori_id']);
});

it('validates numeric fields', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori = SubKategori::factory()->create(['kategori_id' => $kategori->id]);

    Livewire::test(ProdukPpobCrud::class)
        ->set('kode', 'PLN5K')
        ->set('nama_produk', 'PLN Prepaid 5.000')
        ->set('sub_kategori_id', $subKategori->id)
        ->set('hpp', -100) // Invalid negative value
        ->call('save')
        ->assertHasErrors(['hpp']);
});

it('can open edit modal', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori = SubKategori::factory()->create(['kategori_id' => $kategori->id]);
    $produk = ProdukPpob::factory()->create(['sub_kategori_id' => $subKategori->id]);

    Livewire::test(ProdukPpobCrud::class)
        ->call('edit', $produk->id)
        ->assertSet('showModal', true)
        ->assertSet('isEditing', true)
        ->assertSet('nama_produk', $produk->nama_produk);
});

it('can update produk ppob', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori = SubKategori::factory()->create(['kategori_id' => $kategori->id]);
    $produk = ProdukPpob::factory()->create([
        'nama_produk' => 'Old Product Name',
        'kode' => 'OLD',
        'sub_kategori_id' => $subKategori->id,
    ]);

    Livewire::test(ProdukPpobCrud::class)
        ->call('edit', $produk->id)
        ->set('nama_produk', 'New Product Name')
        ->set('kode', 'NEW')
        ->call('save');

    $this->assertDatabaseHas('produk_ppob', [
        'id' => $produk->id,
        'nama_produk' => 'New Product Name',
        'kode' => 'NEW',
    ]);
});

it('can open delete confirmation modal', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori = SubKategori::factory()->create(['kategori_id' => $kategori->id]);
    $produk = ProdukPpob::factory()->create(['sub_kategori_id' => $subKategori->id]);

    Livewire::test(ProdukPpobCrud::class)
        ->call('confirmDelete', $produk->id)
        ->assertSet('showDeleteModal', true)
        ->assertSet('produkId', $produk->id);
});

it('can delete produk ppob', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create();
    $subKategori = SubKategori::factory()->create(['kategori_id' => $kategori->id]);
    $produk = ProdukPpob::factory()->create(['sub_kategori_id' => $subKategori->id]);

    Livewire::test(ProdukPpobCrud::class)
        ->call('confirmDelete', $produk->id)
        ->call('delete');

    $this->assertDatabaseMissing('produk_ppob', [
        'id' => $produk->id,
    ]);
});

it('displays sub kategori information in table', function () {
    $this->actingAs($this->user);

    $kategori = Kategori::factory()->create(['nama' => 'Listrik']);
    $subKategori = SubKategori::factory()->create([
        'kategori_id' => $kategori->id,
        'nama' => 'Token PLN',
    ]);
    $produk = ProdukPpob::factory()->create([
        'nama_produk' => 'PLN 20K',
        'sub_kategori_id' => $subKategori->id,
    ]);

    Livewire::test(ProdukPpobCrud::class)
        ->assertSee('Listrik')
        ->assertSee('Token PLN');
});
