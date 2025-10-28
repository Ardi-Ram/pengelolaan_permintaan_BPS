<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganCustomer extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_customer';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'kode_transaksi',     // Kode transaksi kunjungan
        'tanggal_kunjungan',  // Tanggal kunjungan customer
        'pemilik_id',         // ID pemilik data
    ];

    /**
     * Relasi ke pemilik data
     * Setiap kunjungan terkait dengan satu pemilik data
     */
    public function pemilikData()
    {
        return $this->belongsTo(PemilikData::class, 'pemilik_id');
    }

    /**
     * Relasi ke permintaan data berdasarkan kode transaksi
     * Menghubungkan kunjungan dengan permintaan yang sesuai
     */
    public function permintaanData()
    {
        return $this->belongsTo(PermintaanData::class, 'kode_transaksi', 'kode_transaksi');
    }
}
