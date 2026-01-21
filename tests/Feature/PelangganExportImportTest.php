<?php

use App\Livewire\PelangganCrud;
use App\Models\Pelanggan;
use App\Models\User;
use Livewire\Livewire;

uses()->group('pelanggan', 'export', 'import');

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('has pelanggan crud component', function () {
    $component = Livewire::test(PelangganCrud::class);

    expect($component)->not->toBeNull();
});

it('has export and import functionality', function () {
    $component = Livewire::test(PelangganCrud::class);

    // Assert component has the necessary properties
    expect($component)
        ->get('importMode')->toBe('append')
        ->and($component->get('showImportModal'))->toBe(false);
});

it('validates import file is required', function () {
    Livewire::test(PelangganCrud::class)
        ->call('showImportConfirmation')
        ->assertHasErrors(['importFile' => 'required']);
});

it('can set import mode to append', function () {
    Livewire::test(PelangganCrud::class)
        ->assertSet('importMode', 'append');
});

it('can set import mode to replace', function () {
    Livewire::test(PelangganCrud::class)
        ->set('importMode', 'replace')
        ->assertSet('importMode', 'replace');
});

it('can cancel import and reset to defaults', function () {
    Livewire::test(PelangganCrud::class)
        ->set('importMode', 'replace')
        ->call('cancelImport')
        ->assertSet('showImportModal', false)
        ->assertSet('importMode', 'append');
});

it('pelanggan data can be created for export', function () {
    $pelanggans = Pelanggan::factory()->count(5)->create();

    expect($pelanggans)->toHaveCount(5);
});
