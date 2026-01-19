<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukPpob extends Model
{
    /** @use HasFactory<\Database\Factories\ProdukPpobFactory> */
    use HasFactory;

    protected $table = 'produk_ppob';

    protected $fillable = [
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
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'sub_kategori_id' => 'integer',
            'hpp' => 'decimal:2',
            'biaya_admin' => 'decimal:2',
            'fee_mitra' => 'decimal:2',
            'markup' => 'decimal:2',
            'harga_beli' => 'decimal:2',
            'harga_jual' => 'decimal:2',
            'profit' => 'decimal:2',
            'aktif' => 'boolean',
        ];
    }

    /**
     * Get the sub category that owns the product.
     */
    public function subKategori(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubKategori::class);
    }
}
