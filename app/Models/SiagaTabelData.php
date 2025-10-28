<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiagaTabelData extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi massal
     */
    protected $fillable = [
        'nomor_publikasi',   // Nomor publikasi
        'judul_publikasi',   // Judul publikasi
        'nomor_tabel',       // Nomor tabel
        'judul_tabel',       // Judul tabel
        'nomor_halaman',     // Nomor halaman
        'pengolah_id',       // FK ke user pengolah
        'petugas_pst_id',    // FK ke user petugas PST
        'link_output',       // Link hasil output tabel
        'status',            // Status tabel (draft/selesai/dll)
    ];

    /**
     * Relasi ke user pengolah (belongsTo)
     */
    public function pengolah()
    {
        return $this->belongsTo(User::class, 'pengolah_id');
    }

    /**
     * Relasi ke user petugas PST (belongsTo)
     */
    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }
}
