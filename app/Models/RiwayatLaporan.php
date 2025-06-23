<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatLaporan extends Model
{
    use HasFactory;

    protected $table = 'riwayat_laporan';
    protected $primaryKey = 'riwayat_id';

    protected $fillable = [
        'jenis',
        'judul',
        'deskripsi',
        'status',
        'komentar',
        'file',
        'kontak',
        'users_user_id',
        'laporan_laporan_id',
        'surat_surat_id'
    ];

    // Attributes yang dilindungi dari mass assignment kecuali melalui method khusus
    protected $guarded = [];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    // Default values
    protected $attributes = [
        'status' => 'perlu ditinjau'
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }

    // Relationship dengan Laporan
    public function laporan()
    {
        return $this->belongsTo(Laporan::class, 'laporan_laporan_id', 'laporan_id');
    }

    // Relationship dengan Surat
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'surat_surat_id', 'surat_id');
    }
}
