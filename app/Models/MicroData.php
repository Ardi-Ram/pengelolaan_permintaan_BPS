<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MicroData extends Model
{
    use HasFactory;

    protected $table = 'micro_data';

    protected $fillable = [
        'judul',
        'deskripsi',
        'gambar',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(CategoryData::class, 'kategori_id');
    }
    public function items()
    {
        return $this->hasMany(MicroDataItem::class);
    }
}
