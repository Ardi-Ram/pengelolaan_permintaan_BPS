<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LinkGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
    ];

    public function links()
    {
        return $this->hasMany(Link::class)->orderBy('order');
    }
}
