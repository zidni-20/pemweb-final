<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['nama_kategori' => 'Programming', 'deskripsi' => null, 'icon' => 'code-slash', 'warna' => 'primary'],
            ['nama_kategori' => 'Database', 'deskripsi' => null, 'icon' => 'database', 'warna' => 'success'],
            ['nama_kategori' => 'Web Design', 'deskripsi' => null, 'icon' => 'palette', 'warna' => 'info'],
            ['nama_kategori' => 'Networking', 'deskripsi' => null, 'icon' => 'wifi', 'warna' => 'warning'],
            ['nama_kategori' => 'Data Science', 'deskripsi' => null, 'icon' => 'graph-up', 'warna' => 'danger'],
        ];

        foreach ($data as $row) {
            DB::table('kategori')->updateOrInsert([
                'nama_kategori' => $row['nama_kategori']
            ], $row);
        }
    }
}
