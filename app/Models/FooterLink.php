<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'footer_link_group_id',
        'label',
        'url',
        'order',
    ];

    /**
     * Relasi: link milik satu group
     */
    public function group()
    {
        return $this->belongsTo(FooterLinkGroup::class, 'footer_link_group_id');
    }
}
