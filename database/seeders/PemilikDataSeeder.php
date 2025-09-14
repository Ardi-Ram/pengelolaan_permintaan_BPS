<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PemilikData;
use Illuminate\Support\Str;

class PemilikDataSeeder extends Seeder
{
    public function run()
    {

        foreach (range(1, 5) as $index) {
            PemilikData::create([
                'nama_pemilik' => 'Pemilik Data ' . $index,
                'instansi' => 'Instansi ' . $index,
                'email' => 'pemilik' . $index . '@example.com',
                'no_wa' => '08' . rand(100000000, 999999999),
                'kode_transaksi' => Str::random(8),
            ]);
        }
    }
}
