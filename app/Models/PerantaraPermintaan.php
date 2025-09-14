<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerantaraPermintaan extends Model
{
    protected $table = 'perantara_data';
    protected $fillable = ['nama_perantara'];

    public function pemilikData()
    {
        return $this->hasMany(PemilikData::class, 'perantara_id');
    }
}
