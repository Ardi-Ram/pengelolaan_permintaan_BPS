<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Kolom yang bisa diisi massal
     */
    protected $fillable = [
        'name',     // Nama user
        'email',    // Email user
        'password', // Password (akan di-hash otomatis)
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut ke tipe tertentu
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Waktu verifikasi email
            'password' => 'hashed',            // Password otomatis di-hash
        ];
    }

    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Cek apakah user adalah petugas PST
     */
    public function isPetugas()
    {
        return $this->hasRole('petugas_pst');
    }

    /**
     * Cek apakah user adalah pengolah data
     */
    public function isPengolah()
    {
        return $this->hasRole('pengolah_data');
    }

    /**
     * Relasi: user sebagai petugas PST menangani permintaan data
     */
    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class, 'petugas_pst_id');
    }

    /**
     * Relasi: user sebagai pengolah data menangani permintaan
     */
    public function permintaanPengolah()
    {
        return $this->hasMany(PermintaanData::class, 'pengolah_id');
    }
}
