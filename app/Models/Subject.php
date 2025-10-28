<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    /**
     * Kolom yang bisa diisi massal
     */
    protected $fillable = [
        'category_data_id', // FK ke kategori data
        'nama_subject',     // Nama subject
    ];

    /**
     * Relasi ke kategori data (belongsTo)
     */
    public function category()
    {
        return $this->belongsTo(CategoryData::class, 'category_data_id');
    }

    /**
     * Relasi ke parent subject (opsional, jika ada hierarki)
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Relasi ke permintaan data yang menggunakan subject ini (hasMany)
     */
    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class, 'subject_id');
    }
}
