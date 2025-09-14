<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemilikData extends Model
{
    use HasFactory;

    protected $table = 'pemilik_data';

    // Pastikan semua field yang akan diisi ada di fillable
    protected $fillable = ['nama_pemilik', 'instansi', 'email', 'no_wa', 'kode_transaksi', 'perantara_id'];


    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class, 'pemilik_data_id');
    }

    public function perantara()
    {
        return $this->belongsTo(PerantaraPermintaan::class, 'perantara_id');
    }
}
