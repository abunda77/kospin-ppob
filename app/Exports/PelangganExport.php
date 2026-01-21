<?php

namespace App\Exports;

use App\Models\Pelanggan;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelangganExport implements FromQuery, WithChunkReading, WithHeadings, WithMapping, WithStyles
{
    public function query()
    {
        return Pelanggan::query()->orderBy('nama');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'No. HP',
            'Alamat',
            'Kota',
            'Provinsi',
            'Kode Pos',
            'Status',
            'Catatan',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    public function map($pelanggan): array
    {
        return [
            $pelanggan->id,
            $pelanggan->nama,
            $pelanggan->email,
            $pelanggan->no_hp,
            $pelanggan->alamat,
            $pelanggan->kota,
            $pelanggan->provinsi,
            $pelanggan->kode_pos,
            $pelanggan->aktif ? 'Aktif' : 'Nonaktif',
            $pelanggan->catatan,
            $pelanggan->created_at?->format('Y-m-d H:i:s'),
            $pelanggan->updated_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function chunkSize(): int
    {
        return 1000; // Process 1000 records at a time
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
