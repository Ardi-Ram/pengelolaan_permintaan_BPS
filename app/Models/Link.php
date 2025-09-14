<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'link_group_id',
        'label',
        'url',
        'order',
    ];

    public function group()
    {
        return $this->belongsTo(LinkGroup::class);
    }
}
