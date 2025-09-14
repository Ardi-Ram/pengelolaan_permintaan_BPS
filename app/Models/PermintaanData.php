<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanData extends Model
{
    use HasFactory;

    protected $table = 'permintaan_data';
    protected $fillable = [
        'pemilik_data_id',
        'petugas_pst_id',
        'pengolah_id',
        'judul_permintaan',
        'deskripsi',
        'kategori_id',
        'subject_id',
        'status',
        'upload_path',
        'alasan'
    ];

    public function petugasPst()
    {
        return $this->belongsTo(User::class, 'petugas_pst_id');
    }
    public function pemilikData()
    {
        return $this->belongsTo(PemilikData::class, 'pemilik_data_id');
    }
    public function kategori()
    {
        return $this->belongsTo(CategoryData::class, 'kategori_id');
    }

    public function pengolah()
    {
        return $this->belongsTo(User::class, 'pengolah_id');
    }

    // app/Models/PermintaanData.php

    public function hasilOlahan()
    {
        return $this->hasOne(HasilOlahan::class, 'permintaan_data_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function perantara()
    {
        return $this->belongsTo(PerantaraPermintaan::class, 'perantara_id');
    }
}
