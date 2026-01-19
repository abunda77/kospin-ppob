<?php

use App\Models\Kategori;
use App\Models\ProdukPpob;
use App\Models\SubKategori;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('can create sub kategori', function () {
    $kategori = Kategori::create(['nama' => 'Parent', 'kode' => 'PAR']);

    $subKategori = SubKategori::create([
        'kategori_id' => $kategori->id,
        'nama' => 'Test Sub Kategori',
    ]);

    expect($subKategori->nama)->toBe('Test Sub Kategori')
        ->and($subKategori->kategori_id)->toBe($kategori->id)
        ->and($subKategori->aktif)->toBeTrue();
});

test('sub kategori belongs to kategori', function () {
    $kategori = Kategori::create(['nama' => 'Parent Kategori',  'kode' => 'PAR']);
    $subKategori = SubKategori::create(['kategori_id' => $kategori->id, 'nama' => 'Child']);

    expect($subKategori->kategori)->toBeInstanceOf(Kategori::class)
        ->and($subKategori->kategori->nama)->toBe('Parent Kategori');
});

test('sub kategori can have many produk ppob', function () {
    $subKategori = SubKategori::create([
        'kategori_id' => Kategori::create(['nama' => 'Test', 'kode' => 'TST'])->id,
        'nama' => 'Sub Test',
    ]);

    ProdukPpob::create([
        'kode' => 'P1',
        'nama_produk' => 'Produk 1',
        'sub_kategori_id' => $subKategori->id,
    ]);

    ProdukPpob::create([
        'kode' => 'P2',
        'nama_produk' => 'Produk 2',
        'sub_kategori_id' => $subKategori->id,
    ]);

    ProdukPpob::create([
        'kode' => 'P3',
        'nama_produk' => 'Produk 3',
        'sub_kategori_id' => $subKategori->id,
    ]);

    ProdukPpob::create([
        'kode' => 'P4',
        'nama_produk' => 'Produk 4',
        'sub_kategori_id' => $subKategori->id,
    ]);

    ProdukPpob::create([
        'kode' => 'P5',
        'nama_produk' => 'Produk 5',
        'sub_kategori_id' => $subKategori->id,
    ]);

    expect($subKategori->produkPpob)->toHaveCount(5);
});

test('sub kategori has fillable attributes', function () {
    $subKategori = new SubKategori;

    expect($subKategori->getFillable())->toContain(
        'kategori_id',
        'nama',
        'kode',
        'deskripsi',
        'aktif',
        'urutan'
    );
});
