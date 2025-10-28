<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemilikData extends Model
{
    use HasFactory;

    protected $table = 'pemilik_data';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'nama_pemilik',  // Nama pemilik data
        'instansi',      // Nama instansi pemilik
        'email',         // Email pemilik
        'no_wa',         // Nomor WhatsApp
        'kode_transaksi', // Kode transaksi unik
        'perantara_id'   // ID perantara (jika ada)
    ];

    /**
     * Relasi ke model PermintaanData
     * Satu pemilik dapat memiliki banyak permintaan data
     */
    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class, 'pemilik_data_id');
    }

    /**
     * Relasi ke model PerantaraPermintaan
     * Pemilik data bisa terkait dengan satu perantara
     */
    public function perantara()
    {
        return $this->belongsTo(PerantaraPermintaan::class, 'perantara_id');
    }
}
