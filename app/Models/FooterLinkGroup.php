<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FooterLinkGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];

    /**
     * Relasi: satu group punya banyak links
     */
    public function links()
    {
        return $this->hasMany(FooterLink::class)->orderBy('order');
    }
}
