<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MicroDataItem extends Model
{
    // Kolom yang bisa diisi massal
    protected $fillable = [
        'micro_data_id',    // ID data mikro induk
        'judul',            // Judul dataset
        'level_penyajian',  // Level penyajian data (opsional)
        'harga',            // Harga dataset (opsional)
        'ukuran_file',      // Ukuran file dataset (opsional)
        'link',             // Link ke file dataset (opsional)
    ];

    /**
     * Relasi ke MicroData
     * Setiap item ini dimiliki oleh satu MicroData
     * Contoh penggunaan: $item->microData
     */
    public function microData()
    {
        return $this->belongsTo(MicroData::class);
    }
}
