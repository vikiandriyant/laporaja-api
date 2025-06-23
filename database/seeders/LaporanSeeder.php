<?php

namespace Database\Seeders;

use App\Models\Laporan;
use Illuminate\Database\Seeder;

class LaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $laporan = [
            [
                'lokasi_kejadian' => 'Jl. Malioboro No. 15, Yogyakarta',
                'tanggal_kejadian' => '2024-01-15 08:30:00',
                'kategori_kategori_id' => 1
            ],
            [
                'lokasi_kejadian' => 'Jl. Sultan Agung No. 23, Yogyakarta',
                'tanggal_kejadian' => '2024-02-20 10:45:00',
                'kategori_kategori_id' => 2
            ],
            [
                'lokasi_kejadian' => 'Jl. Gejayan No. 45, Depok, Sleman',
                'tanggal_kejadian' => '2024-03-10 14:20:00',
                'kategori_kategori_id' => 3
            ],
            [
                'lokasi_kejadian' => 'Jl. Kaliurang KM 14, Sleman',
                'tanggal_kejadian' => '2024-04-05 09:15:00',
                'kategori_kategori_id' => 4
            ],
            [
                'lokasi_kejadian' => 'Jl. Parangtritis No. 67, Bantul',
                'tanggal_kejadian' => '2024-05-15 11:30:00',
                'kategori_kategori_id' => 1
            ],
            [
                'lokasi_kejadian' => 'Jl. Wates KM 12, Kulonprogo',
                'tanggal_kejadian' => '2024-06-20 13:45:00',
                'kategori_kategori_id' => 2
            ],
            [
                'lokasi_kejadian' => 'Jl. Wonosari No. 34, Gunungkidul',
                'tanggal_kejadian' => '2024-07-10 16:20:00',
                'kategori_kategori_id' => 3
            ],
            [
                'lokasi_kejadian' => 'Alun-alun Kidul, Yogyakarta',
                'tanggal_kejadian' => '2024-08-05 10:15:00',
                'kategori_kategori_id' => 4
            ],
        ];

        foreach ($laporan as $laporan) {
            Laporan::create($laporan);
        }
    }
}
