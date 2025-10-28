<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLinkGroup extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'name',  // Nama grup footer link
        'order', // Urutan tampil di halaman
    ];

    /**
     * Relasi: satu grup memiliki banyak FooterLink
     * Mengurutkan link berdasarkan kolom 'order'
     */
    public function links()
    {
        return $this->hasMany(FooterLink::class)->orderBy('order');
    }
}
