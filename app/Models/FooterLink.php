<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'footer_link_group_id', // ID grup footer link terkait
        'label',                // Label atau teks link
        'url',                  // URL tujuan link
        'order',                // Urutan tampil di grup
    ];

    /**
     * Relasi ke model FooterLinkGroup
     * Footer link ini termasuk dalam satu grup
     */
    public function group()
    {
        return $this->belongsTo(FooterLinkGroup::class, 'footer_link_group_id');
    }
}
