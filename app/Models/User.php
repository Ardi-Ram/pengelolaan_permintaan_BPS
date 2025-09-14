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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User.php
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isPetugas()
    {
        return $this->hasRole('petugas_pst');
    }

    public function isPengolah()
    {
        return $this->hasRole('pengolah_data');
    }

    public function permintaanData()
    {
        return $this->hasMany(PermintaanData::class, 'petugas_pst_id');
    }

    public function permintaanPengolah()
    {
        return $this->hasMany(PermintaanData::class, 'pengolah_id');
    }
}
