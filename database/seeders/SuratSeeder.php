<?php

namespace Database\Seeders;

use App\Models\Surat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $surat = [
            [
                'jenis_surat' => 'keterangan',
            ],
            [
                'jenis_surat' => 'pengantar',
            ],
        ];

        foreach ($surat as $surat) {
            Surat::create($surat);
        }
    }
}