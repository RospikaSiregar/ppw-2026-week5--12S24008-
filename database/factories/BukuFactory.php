<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'judul' => $this->faker->sentence(4),
            'penulis' => $this->faker->name(),
            'penerbit' => $this->faker->company(),
            'tahun_terbit' => $this->faker->numberBetween(2015, 2024),
            'kategori_id' => Kategori::factory(),
            'stok' => $this->faker->numberBetween(1, 50),
            'sinopsis' => $this->faker->paragraph(),
        ];
    }
}
