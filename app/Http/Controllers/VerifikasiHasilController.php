<?php

namespace App\Http\Controllers;

use App\Models\HasilOlahan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PermintaanDataSelesaiMail;

/**
 * Controller untuk melakukan verifikasi terhadap hasil olahan data.
 * 
 * Mengelola proses verifikasi hasil olahan oleh petugas PST,
 * termasuk validasi file hasil, pemindahan file ke folder final,
 * serta pemberitahuan ke pemilik data melalui email.
 */
class VerifikasiHasilController extends Controller
{
    /**
     * Tampilkan form verifikasi hasil olahan berdasarkan ID hasil olahan.
     *
     * @param  int  $id  ID hasil olahan yang akan diverifikasi.
     * @return \Illuminate\View\View
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function form($id)
    {
        // Ambil data hasil olahan beserta relasi permintaan data
        $hasil = HasilOlahan::with('permintaanData')->findOrFail($id);

        // Buat URL ke file hasil olahan di public storage
        $filePath = $hasil->path_file;
        $fileUrl = asset('storage/' . $filePath);

        // Kirim data ke view verifikasi
        return view('permintaanolahdata.verifikasi', [
            'hasil' => $hasil,
            'fileUrl' => $fileUrl
        ]);
    }

    /**
     * Simpan hasil verifikasi (valid atau tidak valid) terhadap hasil olahan.
     *
     * Jika hasil valid:
     * - File hasil dipindahkan ke folder permanen.
     * - Status permintaan diperbarui menjadi "selesai".
     * - Pemilik data menerima email notifikasi.
     *
     * Jika tidak valid:
     * - Status permintaan dikembalikan ke "proses".
     * - Catatan verifikasi disimpan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id  ID hasil olahan yang diverifikasi.
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function simpan(Request $request, $id)
    {
        // Validasi input verifikasi
        $request->validate([
            'verifikasi_hasil' => 'required|in:valid,tidak_valid',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        // Ambil data hasil olahan beserta permintaan dan pemiliknya
        $hasil = HasilOlahan::with('permintaanData.pemilikData')->findOrFail($id);
        $oldPath = $hasil->path_file;
        $permintaan = $hasil->permintaanData;

        if ($request->verifikasi_hasil === 'valid') {
            // Buat struktur folder berdasarkan tanggal hari ini
            $tanggal = now();
            $tahun = $tanggal->format('Y');
            $bulan = $tanggal->format('m');
            $hari  = $tanggal->format('d');

            $folderTujuan = "hasil_olah/{$tahun}/{$bulan}/{$hari}";
            $namaBaru = str_replace('TEMP_', 'HSL_', $hasil->nama_file);
            $pathBaru = "{$folderTujuan}/{$namaBaru}";

            // Pindahkan file dari folder sementara ke folder tujuan
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $pathBaru);

                // Perbarui data hasil olahan
                $hasil->update([
                    'verifikasi_hasil' => 'valid',
                    'catatan_verifikasi' => $request->catatan_verifikasi,
                    'path_file' => $pathBaru,
                    'nama_file' => $namaBaru,
                ]);
            }

            // Kirim email notifikasi ke pemilik data
            if ($permintaan && $permintaan->pemilikData && $permintaan->pemilikData->email) {
                Mail::to($permintaan->pemilikData->email)
                    ->send(new PermintaanDataSelesaiMail($permintaan));
            }

            // Ubah status permintaan menjadi selesai dan hapus catatan lama
            $permintaan->update([
                'status' => 'selesai',
                'catatan_verifikasi' => null,
            ]);
        } else {
            // Jika hasil tidak valid, simpan catatan verifikasi
            $hasil->update([
                'verifikasi_hasil' => 'tidak_valid',
                'catatan_verifikasi' => $request->catatan_verifikasi,
            ]);

            // Kembalikan status permintaan ke proses
            $permintaan->update([
                'status' => 'proses',
            ]);
        }

        // Redirect ke halaman status permintaan
        return redirect()->route('permintaanolahdata.status')
            ->with('success', 'Verifikasi berhasil disimpan.');
    }
}
