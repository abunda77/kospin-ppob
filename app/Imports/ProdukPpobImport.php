<?php

namespace App\Imports;

use App\Models\ProdukPpob;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ProdukPpobImport implements ToCollection, WithCalculatedFormulas, WithChunkReading, WithHeadingRow, WithValidation
{
    public function __construct() {}

    public function collection(Collection $rows)
    {
        // Note: replace logic is handled in the controller/livewire component before import

        foreach ($rows as $row) {
            // Calculate values if they are missing or if we want to ensure consistency
            $hpp = $this->parseCurrency($row['hpp'] ?? 0);
            $biayaAdmin = $this->parseCurrency($row['biaya_admin'] ?? 0);
            $feeMitra = $this->parseCurrency($row['fee_mitra'] ?? 0);
            $markup = $this->parseCurrency($row['markup'] ?? 0);

            // Handle optional calculated fields
            $hargaBeli = isset($row['harga_beli']) ? $this->parseCurrency($row['harga_beli']) : ($hpp + $biayaAdmin);
            $hargaJual = isset($row['harga_jual']) ? $this->parseCurrency($row['harga_jual']) : ($hargaBeli + $feeMitra + $markup);
            $profit = isset($row['profit']) ? $this->parseCurrency($row['profit']) : ($hargaJual - $hargaBeli);

            ProdukPpob::updateOrCreate(
                ['kode' => $this->castToString($row['kode'])],
                [
                    'nama_produk' => $row['nama_produk'],
                    'sub_kategori_id' => $row['sub_kategori_id'],
                    'hpp' => $hpp,
                    'biaya_admin' => $biayaAdmin,
                    'fee_mitra' => $feeMitra,
                    'markup' => $markup,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                    'profit' => $profit,
                    'aktif' => $this->parseAktif($row['status'] ?? 'Aktif'),
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'kode' => 'required|max:50',
            'nama_produk' => 'required|max:255',
            'sub_kategori_id' => 'required|numeric',
            // Relax validation to allow formatted numbers (e.g. "10.000") to be parsed in collection
            'hpp' => 'nullable',
            'biaya_admin' => 'nullable',
            'fee_mitra' => 'nullable',
            'markup' => 'nullable',
            'harga_beli' => 'nullable',
            'harga_jual' => 'nullable',
            'profit' => 'nullable',
            'status' => 'nullable',
        ];
    }

    protected function castToString($value): ?string
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }

    protected function parseAktif(?string $status): bool
    {
        if ($status === null) {
            return true;
        }

        $status = strtolower(trim($status));

        return in_array($status, ['aktif', 'active', '1', 'yes', 'ya', 'true']);
    }

    public function chunkSize(): int
    {
        return 500; // Process 500 rows at a time
    }

    protected function parseCurrency($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }

        if (empty($value)) {
            return 0.0;
        }

        // Remove Rp, spaces
        $clean = preg_replace('/[^0-9,.-]/', '', (string) $value);

        // Handle Indonesian format (dot = thousand, comma = decimal) vs English
        // If it looks like Indonesian: 10.000 or 10.000,00
        if (strpos($clean, '.') !== false && strpos($clean, ',') !== false) {
            // Both present: 10.000,00 -> remove dot, replace comma with dot
            $clean = str_replace('.', '', $clean);
            $clean = str_replace(',', '.', $clean);
        } elseif (strpos($clean, '.') !== false) {
            // Only dot: could be 10.000 (10000) or 10.5 (10.5)
            // Simple heuristic: if more than 3 digits after last dot, or multiple dots, treat as thousand Sep
            // If exactly 3 digits after dot? Ambiguous.
            // IMPORTANT: In Indonesia, dot is standard thousand separator.
            // We'll assume dot is thousand separator if logic suggests it.
            // But simpler: just strip dots if there is NO comma.
            // Warning: This breaks 10.5 (US).
            // Let's rely on standard assumption: Export was formatted with standard tools, or Template.
            // If template had NO formatting, Excel sends raw number.
            // If User typed "10.000", they mean 10000.
            $parts = explode('.', $clean);
            if (count($parts) > 1) {
                // Check if last part is 3 digits -> likely thousand separator
                // OR if last part is 2 digits -> likely decimal? No, ID uses comma.
                // Let's assume dot is thousand separator and remove it
                $clean = str_replace('.', '', $clean);
            }
        } elseif (strpos($clean, ',') !== false) {
            // Only comma: 10,5 -> 10.5
            $clean = str_replace(',', '.', $clean);
        }

        return (float) $clean;
    }
}
