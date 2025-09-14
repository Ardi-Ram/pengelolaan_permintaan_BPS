<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\CategoryData;
use App\Models\HasilOlahan;
use App\Models\User;

class PermintaanDataRutin extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'permintaan_data_rutin';

    // Kolom yang boleh di-mass-assign
    protected $fillable = [
        'kode_permintaan',
        'judul',
        'deskripsi',
        'kategori_id',
        'pengolah_id',
        'admin_id',
        'tanggal_dibuat',
        'status',
    ];

    /**
     * Relasi ke tabel category_data
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(CategoryData::class, 'kategori_id');
    }

    /**
     * Relasi ke User (pengolah data)
     */
    public function pengolah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengolah_id');
    }

    /**
     * Relasi ke User (admin yang menugaskan)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi ke tabel hasil_olahan
     */
    public function hasilOlahan(): BelongsTo
    {
        return $this->belongsTo(HasilOlahan::class, 'hasil_olahan_id');
    }

    /**
     * Scope untuk mengambil hanya data rutin yang belum selesai
     */
    public function scopePending($query)
    {
        return $query->where('status', 'antrian');
    }

    /**
     * Scope untuk mengambil data rutin yang sedang diproses
     */
    public function scopeInProcess($query)
    {
        return $query->where('status', 'proses');
    }

    /**
     * Scope untuk mengambil data rutin yang sudah selesai
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }
}
