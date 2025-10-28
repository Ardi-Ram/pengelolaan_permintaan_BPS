<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilOlahan extends Model
{
    use HasFactory;

    protected $table = 'hasil_olahan';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'permintaan_data_id',   // ID permintaan data terkait
        'nama_file',            // Nama file hasil olahan
        'path_file',            // Path penyimpanan file di storage
        'verifikasi_hasil',     // Status verifikasi hasil (null/true/false)
        'catatan_verifikasi',   // Catatan dari verifikasi hasil
    ];

    /**
     * Relasi ke model PermintaanData
     * Hasil olahan milik satu permintaan data
     */
    public function permintaanData()
    {
        return $this->belongsTo(PermintaanData::class, 'permintaan_data_id');
    }
}
