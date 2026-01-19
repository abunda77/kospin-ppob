<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SubKategori>
 */
class SubKategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_id' => \App\Models\Kategori::factory(),
            'nama' => fake()->unique()->words(3, true),
            'kode' => strtoupper(fake()->unique()->lexify('SUB-???')),
            'deskripsi' => fake()->sentence(),
            'aktif' => true,
            'urutan' => fake()->numberBetween(0, 100),
        ];
    }
}
