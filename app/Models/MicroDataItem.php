<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicroDataItem extends Model
{
    protected $fillable = [
        'micro_data_id',
        'judul',
        'level_penyajian',
        'harga',
        'ukuran_file',
        'link',
    ];

    public function microData()
    {
        return $this->belongsTo(MicroData::class);
    }
}
