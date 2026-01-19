<?php

use App\Models\Kategori;
use App\Models\ProdukPpob;
use App\Models\SubKategori;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can create produk ppob', function () {
    $subKategori = SubKategori::create([
        'kategori_id' => Kategori::create(['nama' => 'Test', 'kode' => 'TST'])->id,
        'nama' => 'Sub Test',
    ]);

    $produk = ProdukPpob::create([
        'kode' => 'PLN10K',
        'nama_produk' => 'PLN Token 10.000',
        'sub_kategori_id' => $subKategori->id,
    ]);

    expect($produk->kode)->toBe('PLN10K')
        ->and($produk->nama_produk)->toBe('PLN Token 10.000')
        ->and($produk->sub_kategori_id)->toBe($subKategori->id)
        ->and($produk->aktif)->toBeTrue();
});

test('produk ppob belongs to sub kategori', function () {
    $subKategori = SubKategori::create([
        'kategori_id' => Kategori::create(['nama' => 'Test', 'kode' => 'TST'])->id,
        'nama' => 'Test Sub',
    ]);

    $produk = ProdukPpob::create([
        'kode' => 'P1',
        'nama_produk' => 'Produk 1',
        'sub_kategori_id' => $subKategori->id,
    ]);

    expect($produk->subKategori)->toBeInstanceOf(SubKategori::class)
        ->and($produk->subKategori->nama)->toBe('Test Sub');
});

test('produk ppob has correct price attributes', function () {
    $produk = ProdukPpob::create([
        'kode' => 'TEST1',
        'nama_produk' => 'Test Product',
        'hpp' => 10000,
        'biaya_admin' => 1000,
        'fee_mitra' => 500,
        'markup' => 2000,
        'harga_beli' => 11500,
        'harga_jual' => 13500,
        'profit' => 2000,
    ]);

    expect($produk->hpp)->toBe('10000.00')
        ->and($produk->biaya_admin)->toBe('1000.00')
        ->and($produk->harga_jual)->toBe('13500.00');
});

test('produk ppob has fillable attributes', function () {
    $produk = new ProdukPpob;

    expect($produk->getFillable())->toContain(
        'kode',
        'nama_produk',
        'sub_kategori_id',
        'hpp',
        'biaya_admin',
        'fee_mitra',
        'markup',
        'harga_beli',
        'harga_jual',
        'profit',
        'aktif'
    );
});

test('produk ppob casts decimal attributes correctly', function () {
    $produk = ProdukPpob::create([
        'kode' => 'TEST2',
        'nama_produk' => 'Test',
        'hpp' => '10000.50',
    ]);

    expect($produk->hpp)->toBeString()
        ->and($produk->aktif)->toBeBool();
});
