<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pelanggan = [
            [
                'nama' => 'John Doe',
                'email' => 'john.doe@example.com',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Merdeka No. 10, Jakarta Pusat',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '10110',
                'aktif' => true,
                'catatan' => 'Pelanggan VIP',
            ],
            [
                'nama' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'no_hp' => '081234567891',
                'alamat' => 'Jl. Sudirman No. 5, Jakarta Selatan',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '12190',
                'aktif' => true,
                'catatan' => null,
            ],
            [
                'nama' => 'Michael Johnson',
                'email' => 'michael.j@example.com',
                'no_hp' => '081234567892',
                'alamat' => 'Jl. Thamrin No. 15, Jakarta Pusat',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '10310',
                'aktif' => false,
                'catatan' => 'Tidak aktif karena belum verifikasi',
            ],
            [
                'nama' => 'Sarah Wilson',
                'email' => 'sarah.w@example.com',
                'no_hp' => '081234567893',
                'alamat' => 'Jl. Gatot Subroto No. 20, Jakarta Selatan',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '12950',
                'aktif' => true,
                'catatan' => 'Pelanggan tetap',
            ],
            [
                'nama' => 'David Brown',
                'email' => 'david.b@example.com',
                'no_hp' => '081234567894',
                'alamat' => 'Jl. MH Thamrin No. 8, Jakarta Pusat',
                'kota' => 'Jakarta',
                'provinsi' => 'DKI Jakarta',
                'kode_pos' => '10350',
                'aktif' => true,
                'catatan' => null,
            ],
        ];

        foreach ($pelanggan as $item) {
            \App\Models\Pelanggan::create($item);
        }
    }
}
