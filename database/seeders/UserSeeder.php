<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nik' => '1234567890123456',
            'nama_lengkap' => 'admin',
            'no_telepon' => '1234567890',
            'password' => bcrypt('12345678'),
            'role' => 'admin'
        ]);

        User::create([
            'nik' => '1234567890123457',
            'nama_lengkap' => 'customer',
            'no_telepon' => '1234567890',
            'password' => bcrypt('12345678'),
            'role' => 'user'
        ]);
    }
}
