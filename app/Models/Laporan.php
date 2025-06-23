<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $primaryKey = 'laporan_id';

    protected $fillable = [
        'lokasi_kejadian',
        'tanggal_kejadian',
        'kategori_kategori_id'
    ];

    protected $casts = [
        'tanggal_kejadian' => 'datetime'
    ];

    // Relationship dengan Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_kategori_id', 'kategori_id');
    }

    // Relationship dengan RiwayatLaporan
    public function riwayatLaporan()
    {
        return $this->hasMany(RiwayatLaporan::class, 'laporan_laporan_id', 'laporan_id');
    }
}