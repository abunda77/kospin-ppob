<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori>
 */
class KategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->unique()->words(2, true),
            'kode' => strtoupper(fake()->unique()->lexify('KAT-???')),
            'deskripsi' => fake()->sentence(),
            'aktif' => true,
            'urutan' => fake()->numberBetween(0, 100),
        ];
    }
}
