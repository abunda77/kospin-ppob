<?php

use App\Models\Pelanggan;

it('has fillable attributes', function () {
    $pelanggan = new Pelanggan;

    expect($pelanggan->getFillable())->toContain(
        'nama',
        'email',
        'no_hp',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'aktif',
        'catatan'
    );
});

it('uses correct table name', function () {
    $pelanggan = new Pelanggan;

    expect($pelanggan->getTable())->toBe('pelanggan');
});

it('has correct casts', function () {
    $pelanggan = new Pelanggan;

    expect($pelanggan->getCasts())->toHaveKey('aktif')
        ->and($pelanggan->getCasts()['aktif'])->toBe('boolean');
});

it('uses mass assignment correctly', function () {
    $attributes = [
        'nama' => 'Test Customer',
        'email' => 'test@example.com',
        'no_hp' => '081234567890',
        'alamat' => 'Test Address',
        'kota' => 'Jakarta',
        'provinsi' => 'DKI Jakarta',
        'kode_pos' => '12345',
        'aktif' => true,
        'catatan' => 'Test notes',
    ];

    $pelanggan = new Pelanggan($attributes);

    expect($pelanggan->nama)->toBe('Test Customer')
        ->and($pelanggan->email)->toBe('test@example.com')
        ->and($pelanggan->no_hp)->toBe('081234567890')
        ->and($pelanggan->alamat)->toBe('Test Address')
        ->and($pelanggan->kota)->toBe('Jakarta')
        ->and($pelanggan->provinsi)->toBe('DKI Jakarta')
        ->and($pelanggan->kode_pos)->toBe('12345')
        ->and($pelanggan->aktif)->toBeTrue()
        ->and($pelanggan->catatan)->toBe('Test notes');
});

it('allows nullable fields to be null', function () {
    $pelanggan = new Pelanggan([
        'nama' => 'Test Customer',
        'no_hp' => '081234567890',
        'email' => null,
        'alamat' => null,
        'kota' => null,
        'provinsi' => null,
        'kode_pos' => null,
        'catatan' => null,
    ]);

    expect($pelanggan->nama)->toBe('Test Customer')
        ->and($pelanggan->no_hp)->toBe('081234567890')
        ->and($pelanggan->email)->toBeNull()
        ->and($pelanggan->alamat)->toBeNull()
        ->and($pelanggan->kota)->toBeNull()
        ->and($pelanggan->provinsi)->toBeNull()
        ->and($pelanggan->kode_pos)->toBeNull()
        ->and($pelanggan->catatan)->toBeNull();
});

it('casts aktif to boolean from integer', function () {
    $pelanggan = new Pelanggan(['aktif' => 1]);
    expect($pelanggan->aktif)->toBeTrue();

    $pelanggan = new Pelanggan(['aktif' => 0]);
    expect($pelanggan->aktif)->toBeFalse();
});

it('casts aktif to boolean from string', function () {
    $pelanggan = new Pelanggan(['aktif' => '1']);
    expect($pelanggan->aktif)->toBeTrue();

    $pelanggan = new Pelanggan(['aktif' => '0']);
    expect($pelanggan->aktif)->toBeFalse();
});

it('can set and get attributes', function () {
    $pelanggan = new Pelanggan;

    $pelanggan->nama = 'John Doe';
    $pelanggan->email = 'john@example.com';
    $pelanggan->no_hp = '081234567890';

    expect($pelanggan->nama)->toBe('John Doe')
        ->and($pelanggan->email)->toBe('john@example.com')
        ->and($pelanggan->no_hp)->toBe('081234567890');
});
