<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KunjunganCustomer extends Model
{
    use HasFactory;

    protected $table = 'kunjungan_customer';
    protected $fillable = ['kode_transaksi', 'tanggal_kunjungan', 'pemilik_id'];

    public function pemilikData()
    {
        return $this->belongsTo(PemilikData::class, 'pemilik_id');
    }

    public function permintaanData()
    {
        return $this->belongsTo(PermintaanData::class, 'kode_transaksi', 'kode_transaksi');
    }
}
