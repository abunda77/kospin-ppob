<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKategori extends Model
{
    /** @use HasFactory<\Database\Factories\SubKategoriFactory> */
    use HasFactory;

    protected $table = 'sub_kategori';

    protected $fillable = [
        'kategori_id',
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
            'kategori_id' => 'integer',
        ];
    }

    /**
     * Get the category that owns the sub category.
     */
    public function kategori(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Get the products for the sub category.
     */
    public function produkPpob(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ProdukPpob::class);
    }
}
