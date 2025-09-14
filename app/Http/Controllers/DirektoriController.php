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
    public function direktoriView()
    {
        $kategoriList = CategoryData::all();
        $pengolahList = User::role('pengolah_data')->get();
        $petugasList = User::role('petugas_pst')->get();
        $tahunList = PermintaanData::selectRaw('YEAR(created_at) as tahun')
            ->distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        // ⬇ Tambahkan ini untuk cari bulan-tahun file hasil olahan
        $files = PermintaanData::with('hasilOlahan')
            ->whereHas('hasilOlahan', fn($q) => $q->whereNotNull('path_file'))
            ->get();

        $months = []; // contoh: ['2025-05' => 'Mei 2025']

        foreach ($files as $data) {
            $path = $data->hasilOlahan->path_file ?? null;
            if ($path && Storage::disk('public')->exists($path)) {
                $fullPath = storage_path('app/public/' . $path);
                $modified = date('Y-m', filemtime($fullPath)); // ex: 2025-05
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

        $months = array_unique($months);
        krsort($months);

        // ⬇ kirim juga ke view
        return view('permintaanolahdata.direktori', compact('kategoriList', 'pengolahList', 'petugasList', 'tahunList', 'months'));
    }

    public function getDirektoriData(Request $request)
    {
        $user = auth::user();

        $data = PermintaanData::with(['kategori', 'hasilOlahan', 'pengolah', 'petugasPst'])
            ->where('status', 'selesai')
            ->leftJoin('hasil_olahan', 'permintaan_data.id', '=', 'hasil_olahan.permintaan_data_id')
            ->select('permintaan_data.*', 'hasil_olahan.created_at as tanggal_selesai')
            ->orderBy('hasil_olahan.created_at', 'desc');

        if ($user->hasRole('pengolah_data')) {
            $data->where('pengolah_id', $user->id);
        }

        $data->orderBy('created_at', 'desc');

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {

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
            ->addColumn('judul_permintaan', function ($row) {
                return '
                <div class="flex items-center gap-2 max-w-[250px] text-sm text-gray-700" title="' . e($row->judul_permintaan) . '">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                    <span class="truncate max-w-[200px] hover:whitespace-normal hover:bg-white hover:shadow px-1 rounded transition">
                        ' . e($row->judul_permintaan) . '
                    </span>
                </div>';
            })
            ->addColumn('kategori', function ($row) {
                return '
                <span class="truncate max-w-[160px] block hover:whitespace-normal hover:bg-white hover:shadow px-1 rounded transition text-sm text-gray-700"
                    title="' . e($row->kategori?->nama_kategori ?? '-') . '">
                    ' . e($row->kategori?->nama_kategori ?? '-') . '
                </span>';
            })
            ->addColumn(
                'petugas_pst',
                fn($row) => '
        <div class="grid grid-cols-[40px_auto] items-center gap-2">
            <!-- Foto Profil (Inisial) -->
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white font-bold text-sm">
                ' . strtoupper(implode('', array_slice(array_map(fn($w) => mb_substr($w, 0, 1), preg_split('/\s+/', trim($row->petugasPst?->name ?? ''))), 0, 3))) . '
            </div>

            <!-- Nama & Email -->
            <div class="flex flex-col leading-tight">
                <span class="font-medium text-gray-800 truncate max-w-[120px]" title="' . e($row->petugasPst?->name ?? '-') . '">'
                    . e($row->petugasPst?->name ?? '-') . '
                </span>
                <span class="text-xs text-gray-500 truncate max-w-[120px]" title="' . e($row->petugasPst?->email ?? '-') . '">'
                    . e($row->petugasPst?->email ?? '-') . '
                </span>
            </div>
        </div>
    '
            )



            ->addColumn('periode', function ($row) {
                if (!$row->hasilOlahan || !$row->hasilOlahan->created_at) {
                    return '<span class="italic text-gray-400 text-lg">Belum diolah</span>';
                }

                $start = \Carbon\Carbon::parse($row->created_at);
                $end = \Carbon\Carbon::parse($row->hasilOlahan->created_at);

                // Tanggal & Bulan Sama
                if ($start->month == $end->month && $start->year == $end->year) {
                    $periodeText = $start->day . ' s.d ' . $end->day . ' ' . $end->translatedFormat('F Y');
                } else {
                    // Bulan atau tahun berbeda
                    $periodeText = $start->format('d M Y') . ' s.d ' . $end->format('d M Y');
                }

                return '<span class="inline-block text-sm text-gray-700 bg-gray-100 px-2 py-1 rounded">
                ' . e($periodeText) . '
            </span>';
            })


            ->editColumn('status', function ($row) {
                return '<span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">' . ucfirst($row->status) . '</span>';
            })
            ->addColumn('backup_info', function ($row) {
                $backupFolder = storage_path('app/backup');
                $status = '';
                $hasBackup = false;

                if ($row->hasilOlahan && $row->hasilOlahan->created_at) {
                    $year = \Carbon\Carbon::parse($row->hasilOlahan->created_at)->format('Y');
                    $month = \Carbon\Carbon::parse($row->hasilOlahan->created_at)->format('m');

                    $path = $row->hasilOlahan->path_file;
                    $fileExists = $path && Storage::disk('public')->exists($path);

                    // Cek apakah ada file ZIP backup bulan itu
                    $backupFiles = glob("{$backupFolder}/backup_olahandata_{$year}_{$month}_*.zip");

                    if (!empty($backupFiles)) {
                        $hasBackup = true;
                    }

                    if ($hasBackup || !$fileExists) {
                        // Sudah dibackup → file masih ada atau sudah dihapus
                        $status = '<span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs rounded cursor-default">
                Sudah di-backup
            </span>';
                    } else {
                        // Belum dibackup dan file masih ada
                        $status = '<span class="inline-block px-2 py-1 bg-gray-200 text-gray-700 text-xs rounded cursor-default">
                Belum di-backup
            </span>';
                    }
                } else {
                    $status = '<span class="inline-block px-2 py-1 bg-red-100 text-red-700 text-xs rounded cursor-default">
            Tidak ada data hasil olahan
        </span>';
                }

                return $status;
            })

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



            ->rawColumns(['aksi', 'judul_permintaan', 'status', 'periode', 'backup_info', 'petugas_pst', 'kategori'])
            ->make(true);
    }

    public function download($id)
    {
        $user = auth::user();

        if (!$user->hasAnyRole(['admin', 'petugas_pst', 'pengolah_data'])) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses file ini.');
        }

        $data = PermintaanData::with('hasilOlahan')->findOrFail($id);

        if (!$data->hasilOlahan || !Storage::disk('public')->exists($data->hasilOlahan->path_file)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download(
            storage_path('app/public/' . $data->hasilOlahan->path_file),
            $data->hasilOlahan->nama_file
        );
    }


    public function backupByMonth($year, $month)
    {
        $timestamp = date('Ymd_His');
        $backupName = "backup_olahandata_{$year}_{$month}_{$timestamp}.zip";
        $backupPath = storage_path("app/backup/{$backupName}");

        // Pastikan folder backup ada
        if (!file_exists(dirname($backupPath))) {
            mkdir(dirname($backupPath), 0777, true);
            Log::info("Folder backup dibuat: " . dirname($backupPath));
        }

        Log::info("Membuat backup zip di path: " . $backupPath);

        $zip = new \ZipArchive();
        if ($zip->open($backupPath, \ZipArchive::CREATE) === TRUE) {
            // Ambil semua file hasil olahan untuk bulan & tahun tsb
            $files = \App\Models\PermintaanData::with('hasilOlahan')
                ->whereHas('hasilOlahan', fn($q) => $q->whereNotNull('path_file'))
                ->get();

            foreach ($files as $data) {
                $hasilOlahan = $data->hasilOlahan;
                if ($hasilOlahan && $hasilOlahan->created_at) {
                    $fileYear = \Carbon\Carbon::parse($hasilOlahan->created_at)->format('Y');
                    $fileMonth = \Carbon\Carbon::parse($hasilOlahan->created_at)->format('m');

                    if ($fileYear == $year && $fileMonth == $month) {
                        $path = $hasilOlahan->path_file;
                        if ($path && Storage::disk('public')->exists($path)) {
                            $fullPath = storage_path('app/public/' . $path);
                            $relativePath = basename($path);

                            $zip->addFile($fullPath, $relativePath);
                            Log::info("File ditambahkan ke backup: " . $relativePath);
                        }
                    }
                }
            }

            $zip->close();
            Log::info("Backup zip selesai dibuat: " . $backupName);


            // ✅ return: download zip (tanpa deleteFileAfterSend)
            return response()->download($backupPath);
        } else {
            Log::error("Gagal membuka file zip di path: " . $backupPath);
            return back()->with('error', 'Gagal membuat file zip.');
        }
    }

    public function getAvailableBackupMonths()
    {
        $backupFolder = storage_path('app/backup');
        $months = [];

        if (file_exists($backupFolder)) {
            foreach (scandir($backupFolder) as $file) {
                if (preg_match('/^backup_olahandata_(\d{4})_(\d{2})_.*\.zip$/i', $file, $matches)) {
                    $year = $matches[1];
                    $month = $matches[2];

                    $key = "$year-$month";
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

                    $months[$key] = $bulanNama[$month] . ' ' . $year;
                }
            }
        }

        krsort($months); // urut dari terbaru ke terlama
        return response()->json($months);
    }




    public function hapusBackupDanOriginalByMonth(Request $request)
    {
        $bulanTahun = $request->input('bulan_tahun');

        if (!$bulanTahun || !preg_match('/^\d{4}-\d{2}$/', $bulanTahun)) {
            return back()->with('error', 'Format bulan tidak valid.');
        }

        [$year, $month] = explode('-', $bulanTahun);

        $backupFolder = storage_path('app/backup');

        $deletedBackups = [];
        $deletedOriginals = [];

        // 1. Hapus file backup ZIP
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

        // 2. Hapus file original menggunakan Storage facade
        $dataList = \App\Models\HasilOlahan::whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->whereNotNull('path_file')
            ->get();

        Log::info("Found " . count($dataList) . " files for {$month}/{$year}");

        foreach ($dataList as $hasil) {
            $fileName = basename($hasil->path_file);

            // Menggunakan Storage facade dengan disk 'public'
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

                // Debug: Cek isi folder untuk bulan tersebut
                $monthPath = "hasil_olah/{$year}/" . str_pad($month, 2, '0', STR_PAD_LEFT);
                if (Storage::disk('public')->exists($monthPath)) {
                    $filesInMonth = Storage::disk('public')->files($monthPath, true);
                    Log::info("Files found in {$monthPath}: " . count($filesInMonth));
                }
            }
        }

        return back()->with(
            'success',
            'Berhasil menghapus ' . count($deletedBackups) . ' file backup dan ' .
                count($deletedOriginals) . ' file hasil olahan dari ' . $month . '/' . $year
        );
    }




    // private function deleteOldBackups($year, $month, $days = 3)
    // {
    //     $backupFolder = storage_path('app/backup');
    //     if (!file_exists($backupFolder)) {
    //         return;
    //     }

    //     $files = scandir($backupFolder);

    //     foreach ($files as $file) {
    //         if (strpos($file, "backup_olahandata_{$year}_{$month}_") === 0 && str_ends_with($file, '.zip')) {
    //             $filePath = $backupFolder . '/' . $file;
    //             $lastModified = filemtime($filePath);
    //             if (now()->diffInDays(\Carbon\Carbon::createFromTimestamp($lastModified)) >= $days) {
    //                 unlink($filePath);
    //                 Log::info("Backup lama dihapus: " . $file);
    //             }
    //         }
    //     }
    // }
    // private function deleteOldOriginalFiles($days = 3)
    // {
    //     $folder = storage_path('app/public/hasil_olah');

    //     if (!file_exists($folder)) {
    //         Log::warning("Folder hasil_olah tidak ditemukan: " . $folder);
    //         return;
    //     }

    //     // Scan semua subfolder dan file
    //     $iterator = new \RecursiveIteratorIterator(
    //         new \RecursiveDirectoryIterator($folder, \FilesystemIterator::SKIP_DOTS)
    //     );

    //     foreach ($iterator as $fileInfo) {
    //         if ($fileInfo->isFile()) {
    //             $fullPath = $fileInfo->getPathname();
    //             $modified = \Carbon\Carbon::createFromTimestamp($fileInfo->getMTime());
    //             $diffDays = now()->diffInDays($modified);

    //             if ($diffDays >= $days) {
    //                 if (unlink($fullPath)) {
    //                     Log::info("File asli lama dihapus (> {$days} hari): " . $fullPath);
    //                 } else {
    //                     Log::warning("Gagal menghapus file asli lama: " . $fullPath);
    //                 }
    //             }
    //         }
    //     }
    // }
}
