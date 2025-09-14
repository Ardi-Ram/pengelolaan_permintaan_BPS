<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabelDinamis extends Model
{
    use HasFactory;

    protected $table = 'tabel_statistik';

    protected $fillable = [
        'judul',
        'deskripsi',
        'kategori_id',
        'subject_id',
        'petugas_pst_id',
        'pengolah_id',
        'link_hasil',
        'link_publish',
        'status',
        'deadline',
        'alasan_penolakan',
        'verifikasi_pst',       // tambahkan ini
        'verified_at',          // tambahkan ini
        'catatan_verifikasi',   // tambahkan ini
    ];

    /**
     * Relasi ke kategori data.
     */
    public function kategori()
    {
        return $this->belongsTo(CategoryData::class, 'kategori_id');
    }

    /**
     * Relasi ke pengguna yang bertugas sebagai pengolah.
     */
    public function pengolah()
    {
        return $this->belongsTo(User::class, 'pengolah_id');
    }

    /**
     * Relasi ke pengguna yang bertugas sebagai petugas PST.
     */
    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }
    /**
     * Relasi ke subjek (subject data statistik).
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
