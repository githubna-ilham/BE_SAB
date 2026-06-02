<?php

namespace Database\Seeders;

use App\Models\Catatan;
use Illuminate\Database\Seeder;

class CatatanSeeder extends Seeder
{
    public function run(): void
    {
        Catatan::create([
            'judul' => 'Belajar Async/Await',
            'isi' => 'Pahami Future<T> dan await sebelum lanjut ke HTTP.',
            'kategori' => 'Kuliah',
            'dibuat_pada' => now()->subDays(2),
        ]);
        Catatan::create([
            'judul' => 'Tugas Mobile Pertemuan 5',
            'isi' => 'Refactor DbHelper menjadi ApiClient.',
            'kategori' => 'Tugas',
            'dibuat_pada' => now()->subDay(),
        ]);
        Catatan::create([
            'judul' => 'Beli kopi',
            'isi' => 'Sebelum praktikum.',
            'kategori' => 'Pribadi',
            'dibuat_pada' => now(),
        ]);
    }
}
