<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiagaTabelData extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor_publikasi',
        'judul_publikasi',
        'nomor_tabel',
        'judul_tabel',
        'nomor_halaman',
        'pengolah_id',
        'petugas_pst_id',
        'link_output',
        'status',
    ];

    public function pengolah()
    {
        return $this->belongsTo(User::class, 'pengolah_id');
    }

    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }
}
