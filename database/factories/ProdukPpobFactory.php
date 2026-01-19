<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProdukPpob>
 */
class ProdukPpobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hpp = fake()->randomFloat(2, 5000, 500000);
        $biayaAdmin = fake()->randomFloat(2, 0, 5000);
        $feeMitra = fake()->randomFloat(2, 0, 10000);
        $markup = fake()->randomFloat(2, 0, 20000);
        $hargaBeli = $hpp + $biayaAdmin + $feeMitra;
        $hargaJual = $hargaBeli + $markup;
        $profit = $hargaJual - $hargaBeli;

        return [
            'kode' => strtoupper(fake()->unique()->bothify('PROD-###??')),
            'nama_produk' => fake()->words(3, true).' '.fake()->numberBetween(5, 100).'K',
            'sub_kategori_id' => \App\Models\SubKategori::factory(),
            'hpp' => $hpp,
            'biaya_admin' => $biayaAdmin,
            'fee_mitra' => $feeMitra,
            'markup' => $markup,
            'harga_beli' => $hargaBeli,
            'harga_jual' => $hargaJual,
            'profit' => $profit,
            'aktif' => true,
        ];
    }
}
