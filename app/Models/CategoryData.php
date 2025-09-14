<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryData extends Model
{
    use HasFactory;

    protected $table = 'category_data';
    protected $fillable = ['nama_kategori'];

    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'category_data_id');
    }
}
