<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Model MicroData
 *
 * Mewakili data mikro (dataset) yang bisa dikategorikan dan memiliki banyak item dataset.
 */
class MicroData extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'micro_data';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'kategori_id',
    ];

    /**
     * Relasi ke CategoryData
     * MicroData dimiliki oleh satu kategori
     */
    public function kategori()
    {
        return $this->belongsTo(CategoryData::class, 'kategori_id');
    }

    /**
     * Relasi ke MicroDataItem
     * Satu MicroData bisa memiliki banyak item dataset
     */
    public function items()
    {
        return $this->hasMany(MicroDataItem::class);
    }
}
