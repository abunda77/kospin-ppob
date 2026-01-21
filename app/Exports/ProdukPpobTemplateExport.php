<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProdukPpobTemplateExport implements FromArray, WithColumnFormatting, WithHeadings, WithStyles
{
    public function array(): array
    {
        return [
            [
                'PULSA10',
                'Pulsa 10.000',
                1, // Sub Kategori ID
                10000, // HPP
                500, // Biaya Admin
                100, // Fee Mitra
                200, // Markup
                10500, // Harga Beli (calculated)
                10800, // Harga Jual (calculated)
                300, // Profit (calculated)
                'Aktif',
            ],
            [
                'PLN20',
                'Token PLN 20.000',
                2,
                20000,
                500,
                100,
                200,
                20500,
                20800,
                300,
                'Aktif',
            ],
        ];
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama Produk',
            'Sub Kategori ID',
            'HPP',
            'Biaya Admin',
            'Fee Mitra',
            'Markup',
            'Harga Beli',
            'Harga Jual',
            'Profit',
            'Status',
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
            'A' => NumberFormat::FORMAT_TEXT, // Kode
            'D' => NumberFormat::FORMAT_NUMBER_00, // HPP
            'E' => NumberFormat::FORMAT_NUMBER_00,
            'F' => NumberFormat::FORMAT_NUMBER_00,
            'G' => NumberFormat::FORMAT_NUMBER_00,
            'H' => NumberFormat::FORMAT_NUMBER_00,
            'I' => NumberFormat::FORMAT_NUMBER_00,
            'J' => NumberFormat::FORMAT_NUMBER_00,
        ];
    }
}
