<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RiwayatLaporan;

class RiwayatLaporanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $riwayatLaporan = [
            [
                'jenis' => 'laporan',
                'judul' => 'Jalan Berlubang di Merdeka',
                'deskripsi' => 'Jalan di Merdeka terlihat berlubang, memanggil perhatian.',
                'status' => 'dalam proses',
                'komentar' => 'Laporan sedang diverifikasi tim lapangan',
                'file' => 'jalan_berlubang_001.jpg',
                'kontak' => '081234567890',
                'users_user_id' => 2,
                'laporan_laporan_id' => 1,
                'surat_surat_id' => null,
            ],
            [
                'jenis' => 'surat',
                'judul' => 'Permohonan Surat Keterangan Domisili',
                'deskripsi' => 'Permohonan surat keterangan domisili',
                'status' => 'selesai',
                'komentar' => 'Surat telah selesai dibuat dan dapat diambil',
                'file' => null,
                'kontak' => '081234567890',
                'users_user_id' => 2,
                'laporan_laporan_id' => null,
                'surat_surat_id' => 1,
            ],
            [
                'jenis' => 'laporan',
                'judul' => 'Sampah Menumpuk di Taman Kota',
                'deskripsi' => 'Sampah menumpuk di taman kota, memanggil perhatian.',
                'status' => 'perlu ditinjau',
                'komentar' => 'Perlu koordinasi dengan dinas kebersihan',
                'file' => 'sampah_taman_002.jpg',
                'kontak' => '081234567890',
                'users_user_id' => 2,
                'laporan_laporan_id' => 2,
                'surat_surat_id' => null,
            ],
            [
                'jenis' => 'surat',
                'judul' => 'Permohonan Surat Pengantar KTP',
                'deskripsi' => 'Permohonan surat pengantar KTP',
                'status' => 'dalam proses',
                'komentar' => 'Sedang dalam proses verifikasi data',
                'file' => null,
                'kontak' => '081234567890',
                'users_user_id' => 2,
                'laporan_laporan_id' => null,
                'surat_surat_id' => 2,
            ],
        ];

        foreach ($riwayatLaporan as $riwayat) {
            RiwayatLaporan::create($riwayat);
        }
    }
}

