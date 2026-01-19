<?php

use App\Models\Kategori;
use App\Models\SubKategori;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== VERIFIKASI DATA SEEDER ===\n\n";

echo 'Total Kategori: '.Kategori::count()."\n";
echo 'Total SubKategori: '.SubKategori::count()."\n\n";

echo "=== DETAIL KATEGORI ===\n";
Kategori::orderBy('urutan')->get()->each(function ($kategori) {
    $subCount = $kategori->subKategori()->count();
    echo "{$kategori->id}. {$kategori->nama} ({$kategori->kode}) - {$subCount} sub kategori\n";
});

echo "\n=== BREAKDOWN SUB KATEGORI PER KATEGORI ===\n";
Kategori::with('subKategori')->orderBy('urutan')->get()->each(function ($kategori) {
    echo "\n{$kategori->nama}:\n";
    $kategori->subKategori()->orderBy('urutan')->get()->each(function ($sub) {
        echo "  - {$sub->nama} ({$sub->kode})\n";
    });
});
