<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\CategoryData;
use Illuminate\Http\Request;
use App\Models\PermintaanData;
use Yajra\DataTables\DataTables;
use App\Models\PermintaanDataRutin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;



class DirektoriController extends Controller
{
    /**
     * Controller: Direktori Olahan Data
     * Fungsi utama: Menampilkan dan memfilter direktori hasil olahan data
     */
    public function direktoriView()
    {
        // Ambil semua kategori, user pengolah, dan petugas PST
        $kategoriList = CategoryData::all();
        $pengolahList = User::role('pengolah_data')->get();
        $petugasList = User::role('petugas_pst')->get();

        // Ambil daftar tahun dari waktu pembuatan permintaan data
        $tahunList = PermintaanData::selectRaw('YEAR(created_at) as tahun')
            ->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // Ambil data hasil olahan yang memiliki file
        $files = PermintaanData::with('hasilOlahan')
            ->whereHas('hasilOlahan', fn($q) => $q->whereNotNull('path_file'))
            ->get();

        $months = []; // Format: ['2025-05' => 'Mei 2025']

        // Loop setiap file untuk menentukan bulan-tahun terakhir diubah
        foreach ($files as $data) {
            $path = $data->hasilOlahan->path_file ?? null;
            if ($path && Storage::disk('public')->exists($path)) {
                $fullPath = storage_path('app/public/' . $path);
                $modified = date('Y-m', filemtime($fullPath)); // contoh: 2025-05
                [$year, $month] = explode('-', $modified);

                $namaBulan = [
                    '01' => 'Januari',
                    '02' => 'Februari',
                    '03' => 'Maret',
                    '04' => 'April',
                    '05' => 'Mei',
                    '06' => 'Juni',
                    '07' => 'Juli',
                    '08' => 'Agustus',
                    '09' => 'September',
                    '10' => 'Oktober',
                    '11' => 'November',
                    '12' => 'Desember'
                ];
                $months["$year-$month"] = $namaBulan[$month] . ' ' . $year;
            }
        }

        // Hapus duplikat dan urutkan dari terbaru
        $months = array_unique($months);
        krsort($months);

        // Kirim semua data ke view direktori
        return view('permintaanolahdata.direktori', compact('kategoriList', 'pengolahList', 'petugasList', 'tahunList', 'months'));
    }


    /**
     * Ambil data untuk tabel DataTables pada halaman direktori.
     * Memuat hasil olahan yang sudah selesai, dengan filter dinamis.
     */
    public function getDirektoriData(Request $request)
    {
        $user = auth::user();

        // Ambil data permintaan yang sudah selesai beserta relasi
        $data = PermintaanData::with(['kategori', 'hasilOlahan', 'pengolah', 'petugasPst'])
            ->where('status', 'selesai')
            ->leftJoin('hasil_olahan', 'permintaan_data.id', '=', 'hasil_olahan.permintaan_data_id')
            ->select('permintaan_data.*', 'hasil_olahan.created_at as tanggal_selesai')
            ->orderBy('hasil_olahan.created_at', 'desc');

        // Jika role-nya pengolah data, tampilkan hanya datanya sendiri
        if ($user->hasRole('pengolah_data')) {
            $data->where('pengolah_id', $user->id);
        }

        // Urutkan berdasarkan tanggal dibuat
        $data->orderBy('created_at', 'desc');

        // Konfigurasi DataTables
        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                // Filter kategori, petugas, dan tahun
                if ($request->filled('kategori')) {
                    $query->where('kategori_id', $request->kategori);
                }
                if ($request->filled('petugas')) {
                    $query->where('petugas_pst_id', $request->petugas);
                }
                if ($request->filled('tahun')) {
                    $query->whereYear('created_at', $request->tahun);
                }
            })

            ->addIndexColumn()

            // Kolom: Judul permintaan (dengan ikon dan tooltip)
            ->addColumn('judul_permintaan', function ($row) {
                return '
                <div class="flex items-center gap-2 max-w-[250px] text-sm text-gray-700" title="' . e($row->judul_permintaan) . '">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                    <span class="truncate max-w-[200px] hover:whitespace-normal hover:bg-white hover:shadow px-1 rounded transition">
                        ' . e($row->judul_permintaan) . '
                    </span>
                </div>';
            })

            // Kolom: Kategori permintaan
            ->addColumn('kategori', function ($row) {
                return '
                <span class="truncate max-w-[160px] block hover:whitespace-normal hover:bg-white hover:shadow px-1 rounded transition text-sm text-gray-700"
                    title="' . e($row->kategori?->nama_kategori ?? '-') . '">
                    ' . e($row->kategori?->nama_kategori ?? '-') . '
                </span>';
            })

            // Kolom: Petugas PST (menampilkan inisial, nama, dan email)
            ->addColumn('petugas_pst', fn($row) => '
            <div class="grid grid-cols-[40px_auto] items-center gap-2">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white font-bold text-sm">'
                . strtoupper(implode('', array_slice(array_map(fn($w) => mb_substr($w, 0, 1), preg_split('/\s+/', trim($row->petugasPst?->name ?? ''))), 0, 3))) .
                '</div>
                <div class="flex flex-col leading-tight">
                    <span class="font-medium text-gray-800 truncate max-w-[120px]" title="' . e($row->petugasPst?->name ?? '-') . '">' . e($row->petugasPst?->name ?? '-') . '</span>
                    <span class="text-xs text-gray-500 truncate max-w-[120px]" title="' . e($row->petugasPst?->email ?? '-') . '">' . e($row->petugasPst?->email ?? '-') . '</span>
                </div>
            </div>
        ')

            // Kolom: Periode pengerjaan (tanggal mulai–selesai)
            ->addColumn('periode', function ($row) {
                if (!$row->hasilOlahan || !$row->hasilOlahan->created_at) {
                    return '<span class="italic text-gray-400 text-lg">Belum diolah</span>';
                }

                $start = \Carbon\Carbon::parse($row->created_at);
                $end = \Carbon\Carbon::parse($row->hasilOlahan->created_at);

                // Jika bulan dan tahun sama → tampilkan s.d format singkat
                if ($start->month == $end->month && $start->year == $end->year) {
                    $periodeText = $start->day . ' s.d ' . $end->day . ' ' . $end->translatedFormat('F Y');
                } else {
                    $periodeText = $start->format('d M Y') . ' s.d ' . $end->format('d M Y');
                }

                return '<span class="inline-block text-sm text-gray-700 bg-gray-100 px-2 py-1 rounded">'
                    . e($periodeText) . '</span>';
            })

            // Kolom: Status (selalu "selesai")
            ->editColumn(
                'status',
                fn($row) =>
                '<span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">'
                    . ucfirst($row->status) . '</span>'
            )

            // Kolom: Status backup file hasil olahan
            ->addColumn('backup_info', function ($row) {
                $backupFolder = storage_path('app/backup');
                $status = '';
                $hasBackup = false;

                if ($row->hasilOlahan && $row->hasilOlahan->created_at) {
                    $year = \Carbon\Carbon::parse($row->hasilOlahan->created_at)->format('Y');
                    $month = \Carbon\Carbon::parse($row->hasilOlahan->created_at)->format('m');

                    $path = $row->hasilOlahan->path_file;
                    $fileExists = $path && Storage::disk('public')->exists($path);

                    $backupFiles = glob("{$backupFolder}/backup_olahandata_{$year}_{$month}_*.zip");
                    if (!empty($backupFiles)) $hasBackup = true;

                    if ($hasBackup || !$fileExists) {
                        $status = '<span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs rounded">Sudah di-backup</span>';
                    } else {
                        $status = '<span class="inline-block px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded">Belum di-backup</span>';
                    }
                } else {
                    $status = '<span class="inline-block px-2 py-1 bg-red-100 text-red-700 text-xs rounded">Tidak ada data hasil olahan</span>';
                }

                return $status;
            })

            // Kolom: Tombol download hasil olahan
            ->addColumn('aksi', function ($row) {
                $path = $row->hasilOlahan?->path_file;
                if ($path && Storage::disk('public')->exists($path)) {
                    return '<a href="' . route('pengolah.direktori.download', $row->id) . '" 
                    class="inline-flex items-center justify-center bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 text-xs"
                    title="Download">
                    <span class="material-symbols-outlined text-sm">download</span>
                </a>';
                }
                return '<span class="text-gray-400 text-xs italic">File tidak tersedia</span>';
            })

            // Izinkan HTML di kolom tertentu
            ->rawColumns(['aksi', 'judul_permintaan', 'status', 'periode', 'backup_info', 'petugas_pst', 'kategori'])
            ->make(true);
    }


    /**
     * Unduh file hasil olahan berdasarkan ID permintaan data.
     */
    public function download($id)
    {
        $user = auth::user();

        // 🔒 Cek role user, hanya role tertentu yang boleh mengunduh
        if (!$user->hasAnyRole(['admin', 'petugas_pst', 'pengolah_data'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses file ini.');
        }

        // Ambil data permintaan beserta relasi hasil olahan
        $data = PermintaanData::with('hasilOlahan')->findOrFail($id);

        // Jika file tidak ada di storage, kirim pesan error
        if (!$data->hasilOlahan || !Storage::disk('public')->exists($data->hasilOlahan->path_file)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        // ✅ Unduh file dari storage/public sesuai path dan nama aslinya
        return response()->download(
            storage_path('app/public/' . $data->hasilOlahan->path_file),
            $data->hasilOlahan->nama_file
        );
    }


    /**
     * Membuat backup (ZIP) hasil olahan berdasarkan bulan & tahun tertentu.
     * Hasilnya akan disimpan di folder /storage/app/backup/
     */
    public function backupByMonth($year, $month)
    {
        $timestamp = date('Ymd_His');
        $backupName = "backup_olahandata_{$year}_{$month}_{$timestamp}.zip";
        $backupPath = storage_path("app/backup/{$backupName}");

        // Pastikan folder backup tersedia
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0777, true);
            Log::info("Folder backup dibuat: " . dirname($backupPath));
        }

        Log::info("Membuat backup zip di path: " . $backupPath);

        $zip = new \ZipArchive();

        // Mulai proses pembuatan file ZIP
        if ($zip->open($backupPath, \ZipArchive::CREATE) === TRUE) {

            // Ambil semua hasil olahan yang punya file
            $files = \App\Models\PermintaanData::with('hasilOlahan')
                ->whereHas('hasilOlahan', fn($q) => $q->whereNotNull('path_file'))
                ->get();

            foreach ($files as $data) {
                $hasilOlahan = $data->hasilOlahan;

                if ($hasilOlahan && $hasilOlahan->created_at) {
                    // Filter berdasarkan tahun & bulan hasil olahan
                    $fileYear = \Carbon\Carbon::parse($hasilOlahan->created_at)->format('Y');
                    $fileMonth = \Carbon\Carbon::parse($hasilOlahan->created_at)->format('m');

                    if ($fileYear == $year && $fileMonth == $month) {
                        $path = $hasilOlahan->path_file;

                        // Tambahkan ke file ZIP jika file valid dan masih ada
                        if ($path && Storage::disk('public')->exists($path)) {
                            $fullPath = storage_path('app/public/' . $path);
                            $relativePath = basename($path);

                            $zip->addFile($fullPath, $relativePath);
                            Log::info("File ditambahkan ke backup: " . $relativePath);
                        }
                    }
                }
            }

            // Tutup ZIP setelah semua file ditambahkan
            $zip->close();
            Log::info("Backup zip selesai dibuat: " . $backupName);

            // ✅ Beri file hasil backup untuk diunduh user
            return response()->download($backupPath);
        } else {
            // Gagal membuat ZIP
            Log::error("Gagal membuka file zip di path: " . $backupPath);
            return back()->with('error', 'Gagal membuat file zip.');
        }
    }

    /**
     * Ambil daftar bulan yang memiliki file backup.
     * 
     * Metode ini memindai folder `storage/app/backup` dan mencari file zip
     * dengan pola nama `backup_olahandata_{tahun}_{bulan}_...zip`.
     * Hasilnya dikembalikan dalam format JSON berisi pasangan bulan-tahun.
     */
    public function getAvailableBackupMonths()
    {
        $backupFolder = storage_path('app/backup');
        $months = [];

        if (file_exists($backupFolder)) {
            foreach (scandir($backupFolder) as $file) {
                // Cek apakah nama file sesuai pola backup yang diharapkan
                if (preg_match('/^backup_olahandata_(\d{4})_(\d{2})_.*\.zip$/i', $file, $matches)) {
                    $year = $matches[1];
                    $month = $matches[2];

                    // Nama bulan dalam bahasa Indonesia
                    $bulanNama = [
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ];

                    // Simpan dengan format "YYYY-MM" => "NamaBulan Tahun"
                    $months["$year-$month"] = $bulanNama[$month] . ' ' . $year;
                }
            }
        }

        // Urut dari yang terbaru ke yang lama
        krsort($months);
        return response()->json($months);
    }

    /**
     * Hapus file backup ZIP dan file hasil olahan asli berdasarkan bulan & tahun tertentu.
     * 
     * Langkah-langkah:
     * 1. Validasi format bulan (YYYY-MM).
     * 2. Hapus semua file zip backup yang sesuai.
     * 3. Hapus file hasil olahan (asli) di storage/public berdasarkan tanggal pembuatan.
     * 4. Catat log penghapusan dan kembalikan pesan hasil operasi.
     */
    public function hapusBackupDanOriginalByMonth(Request $request)
    {
        $bulanTahun = $request->input('bulan_tahun');

        // Validasi format input
        if (!$bulanTahun || !preg_match('/^\d{4}-\d{2}$/', $bulanTahun)) {
            return back()->with('error', 'Format bulan tidak valid.');
        }

        [$year, $month] = explode('-', $bulanTahun);
        $backupFolder = storage_path('app/backup');

        $deletedBackups = [];
        $deletedOriginals = [];

        /** -------------------------------
         * 1️⃣  Hapus file backup ZIP
         * --------------------------------*/
        $backupFiles = glob("{$backupFolder}/backup_olahandata_{$year}_{$month}_*.zip");

        if (empty($backupFiles)) {
            return back()->with('error', 'Tidak ada file backup untuk periode tersebut.');
        }

        foreach ($backupFiles as $zipPath) {
            $zip = new \ZipArchive();
            if ($zip->open($zipPath) === true) {
                $zip->close();
                if (unlink($zipPath)) {
                    $deletedBackups[] = basename($zipPath);
                    Log::info("Backup dihapus: " . basename($zipPath));
                }
            }
        }

        /** -------------------------------
         * 2️⃣  Hapus file hasil olahan original
         * --------------------------------*/
        $dataList = \App\Models\HasilOlahan::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereNotNull('path_file')
            ->get();

        Log::info("Found " . count($dataList) . " files for {$month}/{$year}");

        foreach ($dataList as $hasil) {
            $fileName = basename($hasil->path_file);

            if (Storage::disk('public')->exists($hasil->path_file)) {
                try {
                    if (Storage::disk('public')->delete($hasil->path_file)) {
                        $deletedOriginals[] = $fileName;
                        Log::info("✓ File berhasil dihapus: " . $fileName);
                    } else {
                        Log::error("✗ Storage::delete() gagal untuk: " . $fileName);
                    }
                } catch (\Exception $e) {
                    Log::error("✗ Exception saat hapus file {$fileName}: " . $e->getMessage());
                }
            } else {
                Log::warning("⚠ File tidak ditemukan di storage: " . $hasil->path_file);

                // Debug isi folder jika file hilang
                $monthPath = "hasil_olah/{$year}/" . str_pad($month, 2, '0', STR_PAD_LEFT);
                if (Storage::disk('public')->exists($monthPath)) {
                    $filesInMonth = Storage::disk('public')->files($monthPath, true);
                    Log::info("Files found in {$monthPath}: " . count($filesInMonth));
                }
            }
        }

        /** -------------------------------
         * 3️⃣  Kembalikan hasil operasi
         * --------------------------------*/
        return back()->with(
            'success',
            'Berhasil menghapus ' . count($deletedBackups) . ' file backup dan ' .
                count($deletedOriginals) . ' file hasil olahan dari ' . $month . '/' . $year
        );
    }
}
