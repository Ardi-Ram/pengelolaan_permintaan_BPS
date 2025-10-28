<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    // Kolom yang bisa diisi massal
    protected $fillable = [
        'link_group_id', // ID grup link terkait
        'label',         // Label atau teks link
        'url',           // URL tujuan link
        'order',         // Urutan tampil di grup
    ];

    /**
     * Relasi ke model LinkGroup
     * Link ini termasuk dalam satu grup
     */
    public function group()
    {
        return $this->belongsTo(LinkGroup::class);
    }
}
