<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model CategoryData
 *
 * Mewakili kategori data yang dapat digunakan untuk permintaan data
 * dan mengelompokkan subjects atau sub-topik terkait.
 */
class CategoryData extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'category_data';

    // Kolom yang bisa diisi massal
    protected $fillable = ['nama_kategori'];

    /**
     * Relasi ke PermintaanData
     * Satu kategori bisa memiliki banyak permintaan data
     */
    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class);
    }

    /**
     * Relasi ke Subject
     * Satu kategori bisa memiliki banyak subject
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'category_data_id');
    }
}
