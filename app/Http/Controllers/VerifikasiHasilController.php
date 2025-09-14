<?php

namespace App\Http\Controllers;

use App\Models\HasilOlahan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\PermintaanDataSelesaiMail;


class VerifikasiHasilController extends Controller
{
    /**
     * Menampilkan form verifikasi hasil olahan.
     */
    public function form($id)
    {
        $hasil = HasilOlahan::with('permintaanData')->findOrFail($id);

        // Buat URL ke file hasil olahan di public storage
        $filePath = $hasil->path_file;
        $fileUrl = asset('storage/' . $filePath);

        return view('permintaanolahdata.verifikasi', [
            'hasil' => $hasil,
            'fileUrl' => $fileUrl
        ]);
    }

    /**
     * Simpan hasil verifikasi (valid / tidak valid).
     */
    public function simpan(Request $request, $id)
    {
        $request->validate([
            'verifikasi_hasil' => 'required|in:valid,tidak_valid',
            'catatan_verifikasi' => 'nullable|string|max:1000',
        ]);

        $hasil = HasilOlahan::with('permintaanData.pemilikData')->findOrFail($id);
        $oldPath = $hasil->path_file;
        $permintaan = $hasil->permintaanData;

        if ($request->verifikasi_hasil === 'valid') {
            $tanggal = now();
            $tahun = $tanggal->format('Y');
            $bulan = $tanggal->format('m');
            $hari  = $tanggal->format('d');

            $folderTujuan = "hasil_olah/{$tahun}/{$bulan}/{$hari}";
            $namaBaru = str_replace('TEMP_', 'HSL_', $hasil->nama_file);
            $pathBaru = "{$folderTujuan}/{$namaBaru}";

            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->move($oldPath, $pathBaru);

                $hasil->update([
                    'verifikasi_hasil' => 'valid',
                    'catatan_verifikasi' => $request->catatan_verifikasi,
                    'path_file' => $pathBaru,
                    'nama_file' => $namaBaru,
                ]);
            }

            // Kirim email ke pemilik data
            if ($permintaan && $permintaan->pemilikData && $permintaan->pemilikData->email) {
                Mail::to($permintaan->pemilikData->email)->send(new PermintaanDataSelesaiMail($permintaan));
            }

            // Kosongkan kolom alasan karena data sudah valid
            $permintaan->update([
                'status' => 'selesai',
                'catatan_verifikasi' => null,
            ]);
        } else {
            // Jika hasil tidak valid
            $hasil->update([
                'verifikasi_hasil' => 'tidak_valid',
                'catatan_verifikasi' => $request->catatan_verifikasi, // tetap disimpan di hasil_olahan
            ]);

            $permintaan->update([
                'status' => 'proses',
            ]);
        }

        return redirect()->route('permintaanolahdata.status')->with('success', 'Verifikasi berhasil disimpan.');
    }
}
