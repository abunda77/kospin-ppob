<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategori = [
            [
                'nama' => 'PPOB',
                'kode' => 'PPOB',
                'deskripsi' => 'Pembayaran tagihan dan token (PLN, PDAM, BPJS, dll)',
                'urutan' => 1,
            ],
            [
                'nama' => 'Game',
                'kode' => 'GAME',
                'deskripsi' => 'Voucher game dan top up diamond/UC game online',
                'urutan' => 2,
            ],
            [
                'nama' => 'Prabayar',
                'kode' => 'PRABAYAR',
                'deskripsi' => 'Pulsa dan voucher prabayar semua operator',
                'urutan' => 3,
            ],
            [
                'nama' => 'Paket Data',
                'kode' => 'PAKETDATA',
                'deskripsi' => 'Paket data internet semua operator',
                'urutan' => 4,
            ],
        ];

        foreach ($kategori as $item) {
            \App\Models\Kategori::create($item);
        }
    }
}
