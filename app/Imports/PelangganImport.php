<?php

namespace App\Imports;

use App\Models\Pelanggan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PelangganImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function __construct(
        public bool $replaceMode = false
    ) {}

    public function collection(Collection $rows)
    {
        // If replace mode, delete all existing records
        if ($this->replaceMode) {
            Pelanggan::query()->delete();
        }

        foreach ($rows as $row) {
            Pelanggan::updateOrCreate(
                ['no_hp' => $this->castToString($row['no_hp'])],
                [
                    'nama' => $row['nama'],
                    'email' => $row['email'] ?? null,
                    'alamat' => $row['alamat'] ?? null,
                    'kota' => $row['kota'] ?? null,
                    'provinsi' => $row['provinsi'] ?? null,
                    'kode_pos' => $this->castToString($row['kode_pos'] ?? null),
                    'aktif' => $this->parseAktif($row['status'] ?? 'Aktif'),
                    'catatan' => $row['catatan'] ?? null,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
            'no_hp' => 'required', // Accept both string and numeric
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:100',
            'provinsi' => 'nullable|string|max:100',
            'kode_pos' => 'nullable', // Accept both string and numeric
            'status' => 'nullable|string',
            'catatan' => 'nullable|string',
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
}
