<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';

    protected $fillable = [
        'nama_kategori',
        'users_user_id'
    ];

    // Relationship dengan User
    public function user()
    {
        return $this->belongsTo(User::class, 'users_user_id', 'id');
    }

    // Relationship dengan Laporan
    public function laporan()
    {
        return $this->hasMany(Laporan::class, 'kategori_kategori_id', 'kategori_id');
    }
}