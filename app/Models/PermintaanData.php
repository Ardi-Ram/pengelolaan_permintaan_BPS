<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanData extends Model
{
    use HasFactory;

    protected $table = 'permintaan_data';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'pemilik_data_id',    // ID pemilik data
        'petugas_pst_id',     // ID petugas PST yang menangani
        'pengolah_id',        // ID pengolah data
        'judul_permintaan',   // Judul permintaan data
        'deskripsi',          // Deskripsi permintaan (opsional)
        'kategori_id',        // ID kategori data
        'subject_id',         // ID subject terkait
        'status',             // Status permintaan (antrian, proses, selesai)
        'upload_path',        // Path file hasil upload (opsional)
        'alasan'              // Alasan penolakan atau catatan (opsional)
    ];

    /**
     * Relasi ke User sebagai petugas PST
     * Setiap permintaan bisa ditangani oleh satu petugas PST
     * Contoh penggunaan: $permintaan->petugasPst
     */
    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }

    /**
     * Relasi ke pemilik data
     * Setiap permintaan dimiliki oleh satu pemilik data
     * Contoh: $permintaan->pemilikData
     */
    public function pemilikData()
    {
        return $this->belongsTo(PemilikData::class, 'pemilik_data_id');
    }

    /**
     * Relasi ke kategori data
     * Setiap permintaan terkait satu kategori
     */
    public function kategori()
    {
        return $this->belongsTo(CategoryData::class, 'kategori_id');
    }

    /**
     * Relasi ke pengolah data
     * Setiap permintaan dapat dikerjakan oleh satu pengolah
     */
    public function pengolah()
    {
        return $this->belongsTo(User::class, 'pengolah_id');
    }

    /**
     * Relasi ke hasil olahan
     * Setiap permintaan bisa memiliki satu hasil olahan
     * Contoh: $permintaan->hasilOlahan
     */
    public function hasilOlahan()
    {
        return $this->hasOne(HasilOlahan::class, 'permintaan_data_id');
    }

    /**
     * Relasi ke subject
     * Setiap permintaan bisa terkait satu subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relasi ke perantara permintaan
     * Setiap permintaan bisa melalui satu perantara
     */
    public function perantara()
    {
        return $this->belongsTo(PerantaraPermintaan::class, 'perantara_id');
    }
}
