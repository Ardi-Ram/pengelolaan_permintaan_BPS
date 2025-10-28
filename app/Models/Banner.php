<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Banner
 *
 * Menyimpan data banner yang digunakan pada tampilan website atau aplikasi.
 * Banner dapat diatur urutannya dan status aktif/tidak aktifnya.
 */
class Banner extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'title',       // Judul banner
        'image_path',  // Path gambar banner di storage/public
        'order',       // Urutan tampil banner
        'is_active',   // Status aktif (1 = aktif, 0 = nonaktif)
    ];
}
