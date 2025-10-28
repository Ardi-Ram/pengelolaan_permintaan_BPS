<?php

// PermintaanOlahData Model
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermintaanOlahData extends Model
{
    use HasFactory;

    protected $table = 'permintaanolahdatas';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'nama',             // Nama pemohon
        'instansi',         // Instansi pemohon
        'email',            // Email pemohon
        'no_wa',            // Nomor WhatsApp pemohon
        'jumlah_data',      // Jumlah data yang diminta
        'alasan',           // Alasan permintaan
        'catatan',          // Catatan tambahan
        'petugas_pst_id',   // ID petugas PST yang menangani
    ];

    /**
     * Relasi ke permintaan data
     * Satu permintaan olah data dapat memiliki banyak permintaan data
     */
    public function data()
    {
        return $this->hasMany(PermintaanData::class);
    }

    /**
     * Relasi ke petugas PST
     * Setiap permintaan olah data dapat ditangani oleh satu petugas PST
     */
    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }
}
