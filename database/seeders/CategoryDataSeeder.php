<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryData;
use App\Models\Subject;

class CategoryDataSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Statistik Demografi dan Sosial' => [
                'Kependudukan dan Migrasi',
                'Tenaga Kerja',
                'Kesehatan',
                'Pendidikan',
                'Konsumsi dan Pendapatan',
                'Perlindungan Sosial',
                'Pemukiman dan Perumahan',
                'Hukum dan Kriminal',
                'Budaya',
                'Aktivitas Politik dan Komunitas',
                'Penggunaan Waktu',
                'Gender dan Kelompok Populasi Khusus',
            ],
            'Statistik Ekonomi' => [
                'Statistik Makroekonomi',
                'Neraca Ekonomi',
                'Statistik Bisnis',
                'Statistik Sektoral',
                'Keuangan Pemerintah, Fiskal dan Statistik Sektor Publik',
                'Perdagangan Internasional dan Neraca Pembayaran',
                'Harga-harga',
                'Biaya Tenaga Kerja',
                'Pertanian, Kehutanan, Perikanan',
                'Energi',
                'Pertambangan, Manufaktur, Konstruksi',
                'Transportasi',
                'Pariwisata',
                'Perbankan, Asuransi',
                'Ilmu Pengetahuan, Teknologi dan Inovasi',
                'Kewirausahaan',
            ],
            'Statistik Lingkungan Hidup dan Multi-Domain' => [
                'Lingkungan',
                'Statistik Regional dan Statistik Area Kecil',
                'Statistik dan Indikator Multi-Domain',
                'Buku Tahunan dan Ringkasan Sejenis',
                'Kondisi Tempat Tinggal, Kemiskinan, dan Permasalahan Sosial Lintas Sektor',
                'Masyarakat Informasi',
                'Globalisasi',
                'Indikator Millenium Development Goals (IMDGs)',
                'Pembangunan Berkelanjutan',
            ],
        ];

        foreach ($data as $kategori => $subjects) {
            $cat = CategoryData::create(['nama_kategori' => $kategori]);

            foreach ($subjects as $subjek) {
                Subject::create([
                    'category_data_id' => $cat->id,
                    'nama_subject' => $subjek,
                ]);
            }
        }
    }
}
