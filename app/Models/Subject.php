<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = ['category_data_id', 'nama_subject'];

    public function category()
    {
        return $this->belongsTo(CategoryData::class, 'category_data_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class, 'subject_id');
    }
}
