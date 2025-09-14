<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PermintaanData;
use App\Models\User;
use App\Models\CategoryData;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PermintaanDataSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');
        $kategoriList = CategoryData::with('subjects')->get(); // pastikan eager load subjects
        $petugasPstUsers = User::role('petugas_pst')->get();
        $pengolahUsers = User::role('pengolah_data')->get();

        $topikList = [
            'Jumlah Penduduk',
            'Sebaran Penduduk',
            'Angka Kelahiran',
            'Angka Kematian',
            'Angka Partisipasi Sekolah SD',
            'Jumlah Sekolah dan Guru',
            'Angka Putus Sekolah',
            'Pola Konsumsi Rumah Tangga',
            'Angka Pengangguran Terbuka',
            'Tenaga Kerja Sektor Informal',
            'Tingkat Kemiskinan',
            'Pendapatan Per Kapita',
            'Jumlah UMKM Aktif',
            'Belanja Daerah per Fungsi',
            'Kondisi Jalan Kabupaten',
            'Akses Listrik Rumah Tangga',
            'Akses Air Bersih',
            'Cakupan Imunisasi Dasar',
            'Kematian Bayi',
            'Gizi Buruk Balita',
            'Rumah Tidak Layak Huni',
            'Data Kriminalitas',
            'Indeks Pembangunan Manusia',
            'Ekspor Impor Daerah',
        ];

        $wilayahList = [
            'Kabupaten Bogor',
            'Kabupaten Bekasi',
            'Kota Depok',
            'Kabupaten Bandung',
            'Kota Surabaya',
            'Kabupaten Sleman',
            'Kabupaten Banyumas',
            'Kota Padang',
            'Kabupaten Gowa',
            'Kabupaten Bantaeng'
        ];

        $statusList = ['antrian', 'proses']; // hanya dua status ini

        for ($i = 0; $i < 100; $i++) {
            $topik = $faker->randomElement($topikList);
            $wilayah = $faker->randomElement($wilayahList);
            $tahun = $faker->numberBetween(2018, 2024);
            $judul = "Data $topik $wilayah Tahun $tahun";

            // Pilih kategori random
            $kategori = $kategoriList->random();

            // Dari kategori tsb, ambil random subject
            $subject = optional($kategori->subjects)->random();

            // 70% ada pengolah_id, 30% null
            $pengolahId = null;
            if ($pengolahUsers->isNotEmpty() && $faker->boolean(70)) {
                $pengolahId = $pengolahUsers->random()->id;
            }

            // created_at mundur beberapa bulan agar bisa tes filter/backup
            $createdAt = now()->subMonths(rand(0, 6))->subDays(rand(0, 28));

            PermintaanData::create([
                'pemilik_data_id' => rand(1, 3),
                'petugas_pst_id' => $petugasPstUsers->random()->id,
                'pengolah_id' => $pengolahId,
                'judul_permintaan' => $judul,
                'subject_id' => $subject ? $subject->id : null,
                'deskripsi' => "Dibutuhkan untuk analisis statistik terkait $topik di $wilayah tahun $tahun.",
                'kategori_id' => $kategori->id,
                'status' => $faker->randomElement($statusList),
                'kode_transaksi' => 'TRX' . strtoupper(Str::random(8)),
                'created_at' => $createdAt,
                'updated_at' => $createdAt->copy()->addDays(rand(1, 30)),
            ]);
        }
    }
}
