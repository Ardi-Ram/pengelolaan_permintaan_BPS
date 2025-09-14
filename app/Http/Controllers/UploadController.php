<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PermintaanData;
use App\Models\PermintaanDataRutin;
use App\Models\HasilOlahan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use App\Models\ActivityLog;
use App\Mail\PermintaanDataSelesaiMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{


    //   public function uploadView()
    //     {
    //         return view('pengolah.upload');
    //     }


    public function getUploadData(Request $request)
    {
        $userId = auth::id();
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $data = PermintaanData::with(['kategori', 'hasilOlahan'])
            ->where('pengolah_id', $userId)
            ->where(function ($q) {
                $q->where('status', 'proses')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'selesai')
                            ->whereHas('hasilOlahan', fn($q3) => $q3->whereNull('verifikasi_hasil'));
                    });
            })
            ->when($search, fn($query) =>
            $query->where('judul_permintaan', 'like', '%' . $search . '%'))
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('pengolah.upload', compact('data', 'perPage', 'search'));
    }







    public function upload(Request $request, $id)
    {
        $request->validate([
            'file_hasil' => 'required|file|max:10240|mimes:xls,xlsx,csv,txt',
        ], [
            'file_hasil.required' => 'File hasil olahan wajib diunggah.',
            'file_hasil.file' => 'File yang diunggah tidak valid.',
            'file_hasil.max' => 'Ukuran file maksimal adalah 10 MB.',
            'file_hasil.mimes' => 'Format file harus berupa .xls, .xlsx, atau .csv.',
        ]);


        $data = PermintaanData::with('pemilikData')->findOrFail($id);

        if ($data->pengolah_id !== Auth::id() || $data->status !== 'proses') {
            return back()->with('error', 'Tidak dapat upload untuk permintaan ini.');
        }

        if ($request->hasFile('file_hasil')) {
            $file = $request->file('file_hasil');

            $originalName = str_replace(' ', '_', $file->getClientOriginalName());
            $timestamp = now()->format('Ymd_His');
            $fileName = "TEMP_{$timestamp}_{$originalName}";
            $tempPath = $file->storeAs('temp_uploads', $fileName, 'public');

            $existing = HasilOlahan::where('permintaan_data_id', $data->id)->first();

            if ($existing) {
                if (Storage::disk('public')->exists($existing->path_file)) {
                    Storage::disk('public')->delete($existing->path_file);
                }

                $existing->update([
                    'nama_file' => $fileName,
                    'path_file' => $tempPath,
                    'verifikasi_hasil' => null,
                    'catatan_verifikasi' => null,
                ]);
            } else {
                HasilOlahan::create([
                    'permintaan_data_id' => $data->id,
                    'nama_file' => $fileName,
                    'path_file' => $tempPath,
                ]);
            }

            $data->update(['status' => 'selesai']);

            return redirect()->back()->with('success', 'File berhasil diupload ke folder sementara. Menunggu verifikasi Petugas PST.');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }
}
