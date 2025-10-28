<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasPST extends Model
{
    use HasFactory;

    protected $table = 'petugas_pst';

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'user_id',  // ID user terkait di tabel users
        'nama',     // Nama petugas PST
        'email',    // Email petugas
        'password', // Password (hashed)
    ];

    /**
     * Relasi: petugas PST terkait dengan satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
