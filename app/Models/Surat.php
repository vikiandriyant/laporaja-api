<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';
    protected $primaryKey = 'surat_id';

    protected $fillable = [
        'jenis_surat'
    ];

    // Relationship dengan RiwayatLaporan
    public function riwayatLaporan()
    {
        return $this->hasMany(RiwayatLaporan::class, 'surat_surat_id', 'surat_id');
    }
}