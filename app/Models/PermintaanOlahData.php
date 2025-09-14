<?php

// PermintaanOlahData Model
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PermintaanOlahData extends Model
{
    use HasFactory;

    protected $table = 'permintaanolahdatas';

    protected $fillable = [
        'nama',
        'instansi',
        'email',
        'no_wa',
        'jumlah_data',
        'alasan',
        'catatan',
        'petugas_pst_id', // Tambahkan kolom petugas_pst_id
    ];

    // Relasi ke model PermintaanData (hasMany)
    public function data()
    {
        return $this->hasMany(PermintaanData::class);
    }

    // Relasi ke model User sebagai petugas PST (belongsTo)
    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }
}
