<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilOlahan extends Model
{
    use HasFactory;

    protected $table = 'hasil_olahan';

    protected $fillable = [
        'permintaan_data_id',
        'nama_file',
        'path_file',
        'verifikasi_hasil',
        'catatan_verifikasi',
    ];


    public function permintaanData()
    {
        return $this->belongsTo(PermintaanData::class, 'permintaan_data_id');
    }
}
