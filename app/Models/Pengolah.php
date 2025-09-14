<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengolah extends Model
{
    use HasFactory;

    protected $table = 'pengolah';

    protected $fillable = [
        'user_id',
        'permintaan_data_id',
        'status_data',
        'file_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permintaanData()
    {
        return $this->belongsTo(PermintaanData::class);
    }
}
