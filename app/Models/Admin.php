<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Admin
 *
 * Digunakan untuk menyimpan data admin yang terhubung ke tabel `admin`.
 * Setiap admin terkait dengan satu user (relasi belongsTo).
 */
class Admin extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'admin';

    // Kolom yang dapat diisi secara mass-assignment
    protected $fillable = [
        'user_id',  // ID user yang terkait
        'nama',     // Nama lengkap admin
        'email',    // Email admin
    ];

    /**
     * Relasi ke model User
     *
     * Setiap admin memiliki satu akun user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
