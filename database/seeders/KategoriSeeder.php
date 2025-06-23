<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kategori;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoris = [
            [
                'nama_kategori' => 'Infrastruktur',
                'users_user_id' => 1
            ],
            [
                'nama_kategori' => 'Kebersihan',
                'users_user_id' => 1
            ],
            [
                'nama_kategori' => 'Keamanan',
                'users_user_id' => 1
            ],
            [
                'nama_kategori' => 'Administrasi',
                'users_user_id' => 1
            ],
        ];
        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}
