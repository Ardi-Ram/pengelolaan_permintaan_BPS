<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabelDinamis;
use App\Models\User;

class TabelDinamisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user secara acak berdasarkan role (Spatie)
        $petugasPst = User::role('petugas_pst')->inRandomOrder()->first();
        $pengolah = User::role('pengolah_data')->inRandomOrder()->first();

        // Validasi
        if (!$petugasPst || !$pengolah) {
            $this->command->error('❌ Tidak ditemukan user dengan role "petugas_pst" atau "pengolah".');
            return;
        }

        $data = [
            [
                'judul' => 'Harapan Lama Sekolah Menurut Jenis Kelamin dan Kabupaten/Kota',
                'deskripsi' => 'Statistik harapan lama sekolah berdasarkan jenis kelamin dan kabupaten/kota di Provinsi Kepulauan Bangka Belitung.',
                'kategori_id' => 1,
                'petugas_pst_id' => $petugasPst->id,
                'pengolah_id' => $pengolah->id,
                'link_hasil' => 'https://babel.bps.go.id/id/statistics-table/2/MTA4NiMy/harapan-lama-sekolah-menurut-jenis-kelamin-dan-menurut-kabupaten-kota--tahun-.html',
                'link_publish' => 'https://babel.bps.go.id/id/statistics-table/2/MTA4NiMy/harapan-lama-sekolah-menurut-jenis-kelamin-dan-menurut-kabupaten-kota--tahun-.html',
                'status' => 'published',
                'deadline' => now()->addDays(60),
            ],
            [
                'judul' => 'Jumlah Sekolah, Guru, dan Murid MI di Bawah Kementerian Agama Menurut Kabupaten/Kota',
                'deskripsi' => 'Data jumlah sekolah, guru, dan murid Madrasah Ibtidaiyah (MI) yang berada di bawah Kementerian Agama per kabupaten/kota di Provinsi Kepulauan Bangka Belitung tahun 2023.',
                'kategori_id' => 1,
                'petugas_pst_id' => $petugasPst->id,
                'pengolah_id' => $pengolah->id,
                'link_hasil' => 'https://babel.bps.go.id/id/statistics-table/3/VEU5c1pGVnZkVkVyY1U5S2EwVnJlVlVyTm5aRVFUMDkjMyMxOTAw/jumlah-sekolah--guru--dan-murid-madrasah-ibtidaiyah--mi--di-bawah-kementerian-agama-menurut-kabupaten-kota-di-provinsi-kepulauan-bangka-belitung.html?year=2023',
                'link_publish' => 'https://babel.bps.go.id/id/statistics-table/3/VEU5c1pGVnZkVkVyY1U5S2EwVnJlVlVyTm5aRVFUMDkjMyMxOTAw/jumlah-sekolah--guru--dan-murid-madrasah-ibtidaiyah--mi--di-bawah-kementerian-agama-menurut-kabupaten-kota-di-provinsi-kepulauan-bangka-belitung.html?year=2023',
                'status' => 'published',
                'deadline' => now()->addDays(45),
            ],
            [
                'judul' => 'Tingkat Kegemaran Membaca Masyarakat dan Unsur Penyusunnya Menurut Kabupaten/Kota',
                'deskripsi' => 'Statistik tingkat kegemaran membaca dan unsur penyusunnya menurut kabupaten/kota di Provinsi Kepulauan Bangka Belitung tahun 2024.',
                'kategori_id' => 1,
                'petugas_pst_id' => $petugasPst->id,
                'pengolah_id' => $pengolah->id,
                'link_hasil' => 'https://babel.bps.go.id/id/statistics-table/3/TlROMldrTjVjVzEwVWtkbmRUSk5abkk0T0U5Q1FUMDkjMyMxOTAw/tingkat-kegemaran-membaca-masyarakat-dan-unsur-penyusunnya-menurut-kabupaten-kota-di-provinsi-kepulauan-bangka-belitung.html?year=2024',
                'link_publish' => 'https://babel.bps.go.id/id/statistics-table/3/TlROMldrTjVjVzEwVWtkbmRUSk5abkk0T0U5Q1FUMDkjMyMxOTAw/tingkat-kegemaran-membaca-masyarakat-dan-unsur-penyusunnya-menurut-kabupaten-kota-di-provinsi-kepulauan-bangka-belitung.html?year=2024',
                'status' => 'published',
                'deadline' => now()->addDays(40),
            ],
        ];

        foreach ($data as $item) {
            TabelDinamis::create($item);
        }

        $this->command->info('✅ Seeder tabel_dinamis berhasil dijalankan.');
    }
}
