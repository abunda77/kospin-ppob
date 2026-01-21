<?php

namespace App\Exports;

use App\Models\ProdukPpob;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukPpobExport implements FromQuery, WithChunkReading, WithHeadings, WithMapping, WithStyles
{
    public function __construct(
        protected ?int $subKategoriId = null
    ) {}

    public function query()
    {
        // Eager load subKategori for efficiency
        $query = ProdukPpob::query()->with(['subKategori']);

        if ($this->subKategoriId) {
            $query->where('sub_kategori_id', $this->subKategoriId);
        }

        return $query->orderBy('nama_produk');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Kode',
            'Nama Produk',
            'Sub Kategori ID',
            'Sub Kategori',
            'HPP',
            'Biaya Admin',
            'Fee Mitra',
            'Markup',
            'Harga Beli',
            'Harga Jual',
            'Profit',
            'Status',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    public function map($produk): array
    {
        return [
            $produk->id,
            $produk->kode,
            $produk->nama_produk,
            $produk->sub_kategori_id,
            $produk->subKategori?->nama ?? '-', // Export name for reference
            $produk->hpp,
            $produk->biaya_admin,
            $produk->fee_mitra,
            $produk->markup,
            $produk->harga_beli,
            $produk->harga_jual,
            $produk->profit,
            $produk->aktif ? 'Aktif' : 'Nonaktif',
            $produk->created_at?->format('Y-m-d H:i:s'),
            $produk->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
