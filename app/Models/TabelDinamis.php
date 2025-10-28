<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelDinamis extends Model
{
    use HasFactory;

    protected $table = 'tabel_statistik';

    /**
     * Kolom yang bisa diisi massal
     */
    protected $fillable = [
        'judul',                // Judul tabel/statistik
        'deskripsi',            // Deskripsi tabel/statistik
        'kategori_id',          // FK ke kategori data
        'subject_id',           // FK ke subjek
        'petugas_pst_id',       // FK user petugas PST
        'pengolah_id',          // FK user pengolah
        'link_hasil',           // Link file hasil
        'link_publish',         // Link publikasi
        'status',               // Status permintaan
        'deadline',             // Batas waktu penyelesaian
        'alasan_penolakan',     // Alasan penolakan jika ada
        'verifikasi_pst',       // Status verifikasi oleh PST
        'verified_at',          // Waktu verifikasi
        'catatan_verifikasi',   // Catatan verifikasi
    ];

    /**
     * Relasi ke kategori data
     */
    public function kategori()
    {
        return $this->belongsTo(CategoryData::class, 'kategori_id');
    }

    /**
     * Relasi ke pengguna yang bertugas sebagai pengolah
     */
    public function pengolah()
    {
        return $this->belongsTo(User::class, 'pengolah_id');
    }

    /**
     * Relasi ke pengguna yang bertugas sebagai petugas PST
     */
    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }

    /**
     * Relasi ke subjek (subject data statistik)
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
