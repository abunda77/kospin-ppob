<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PelangganTemplateExport implements FromArray, WithColumnFormatting, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'John Doe',
                'john@example.com',
                '081234567890',
                'Jl. Contoh No. 123',
                'Jakarta',
                'DKI Jakarta',
                '10110',
                'Aktif',
                'Contoh pelanggan',
            ],
            [
                'Jane Smith',
                'jane@example.com',
                '081298765432',
                'Jl. Sample No. 456',
                'Bandung',
                'Jawa Barat',
                '40123',
                'Aktif',
                '',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'No. HP',
            'Alamat',
            'Kota',
            'Provinsi',
            'Kode Pos',
            'Status',
            'Catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_TEXT, // No. HP
            'G' => NumberFormat::FORMAT_TEXT, // Kode Pos
        ];
    }
}
