<?php

use App\Models\Kategori;
use App\Models\SubKategori;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('kategori has fillable attributes', function () {
    $kategori = new Kategori;

    expect($kategori->getFillable())->toContain('nama', 'kode', 'deskripsi', 'aktif', 'urutan');
});

test('can create kategori', function () {
    $kategori = Kategori::create([
        'nama' => 'Test Kategori',
        'kode' => 'TEST',
        'aktif' => true,
        'urutan' => 1,
    ]);

    expect($kategori->nama)->toBe('Test Kategori')
        ->and($kategori->kode)->toBe('TEST')
        ->and($kategori->aktif)->toBeTrue();
});

test('kategori can have many sub kategori', function () {
    $kategori = Kategori::create([
        'nama' => 'Parent',
        'kode' => 'PARENT',
    ]);

    SubKategori::create([
        'kategori_id' => $kategori->id,
        'nama' => 'Child 1',
        'kode' => 'CH1',
    ]);

    SubKategori::create([
        'kategori_id' => $kategori->id,
        'nama' => 'Child 2',
        'kode' => 'CH2',
    ]);

    SubKategori::create([
        'kategori_id' => $kategori->id,
        'nama' => 'Child 3',
        'kode' => 'CH3',
    ]);

    expect($kategori->subKategori)->toHaveCount(3);
});

test('kategori casts attributes correctly', function () {
    $kategori = Kategori::create([
        'nama' => 'Test',
        'aktif' => 1,
        'urutan' => '10',
    ]);

    expect($kategori->aktif)->toBeTrue()
        ->and($kategori->urutan)->toBeInt();
});
