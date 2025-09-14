<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PerantaraPermintaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = ['WhatsApp', 'Telepon', 'Surat', 'Kunjungan Langsung'];
        foreach ($data as $item) {
            \App\Models\PerantaraPermintaan::create(['nama_perantara' => $item]);
        }
    }
}
