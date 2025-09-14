<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasPST extends Model
{
    use HasFactory;

    protected $table = 'petugas_pst';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'password',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
