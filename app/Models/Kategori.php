<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriFactory> */
    use HasFactory;

    protected $table = 'kategori';

    protected $fillable = [
        'nama',
        'kode',
        'deskripsi',
        'aktif',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'aktif' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    /**
     * Get the sub categories for the category.
     */
    public function subKategori(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SubKategori::class);
    }
}
