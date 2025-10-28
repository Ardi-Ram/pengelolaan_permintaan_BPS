<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengolah extends Model
{
    use HasFactory;

    protected $table = 'pengolah';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_id',            // ID user pengolah
        'permintaan_data_id', // ID permintaan data yang diproses
        'status_data',        // Status proses data
        'file_path',          // Path file hasil olahan
    ];

    /**
     * Relasi ke model User
     * Setiap pengolah terkait dengan satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke model PermintaanData
     * Setiap pengolah terkait dengan satu permintaan data
     */
    public function permintaanData()
    {
        return $this->belongsTo(PermintaanData::class);
    }
}
