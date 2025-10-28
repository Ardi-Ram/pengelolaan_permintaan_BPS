<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SiagaTabelData;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SiagaTabelDataController extends Controller
{
    // Pengolah Data

    /**
     * Menampilkan halaman form impor CSV untuk data **Siaga Tabel / Tabel Publikasi**.
     * 
     * View ini akan menampilkan form unggah file CSV dan area pratinjau data setelah file berhasil diimpor.
     *
     * @return \Illuminate\View\View
     */
    public function showImportForm()
    {
        return view('siaga_tabel.import', ['importedData' => []]);
    }


    /**
     * Memproses file CSV yang diunggah untuk data **Siaga Tabel / Tabel Publikasi** dan menampilkan hasil pratinjau.
     * 
     * Melakukan validasi format file (hanya CSV atau TXT), membaca isi file menggunakan `fgetcsv`,
     * dan memastikan struktur header sesuai dengan format yang diharapkan.
     * Jika valid, data akan dikirim ke view untuk ditampilkan sebagai pratinjau sebelum disimpan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function handleImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240',
        ], [
            'file.required' => 'File CSV wajib diunggah.',
            'file.mimes' => 'File harus berformat CSV atau TXT.',
            'file.max' => 'Ukuran file tidak boleh melebihi 10MB.',
        ]);

        $data = [];
        $file = $request->file('file');

        if (($handle = fopen($file->getRealPath(), 'r')) !== false) {
            $header = fgetcsv($handle, 0, ';');
            $expectedHeaders = [
                'nomor_publikasi',
                'judul_publikasi',
                'nomor_tabel',
                'judul_tabel',
                'nomor_halaman'
            ];
            $header = array_map(fn($h) => str_replace(' ', '_', strtolower(trim($h))), $header);

            if (count(array_intersect($expectedHeaders, $header)) !== count($expectedHeaders)) {
                fclose($handle);
                return redirect()->back()->with('error', 'Header CSV tidak lengkap atau tidak sesuai. Pastikan ada: ' . implode(', ', $expectedHeaders));
            }

            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                if (count($header) == count($row)) {
                    $data[] = array_combine($header, $row);
                } else {
                    Log::warning('Baris ' . (count($data) + 2) . ' pada file CSV memiliki jumlah kolom yang tidak sesuai.');
                }
            }
            fclose($handle);
        } else {
            return redirect()->back()->with('error', 'Gagal membaca file.');
        }

        if (empty($data)) {
            return redirect()->back()->with('error', 'File CSV kosong atau tidak ada data yang valid.');
        }

        return view('siaga_tabel.import', ['importedData' => $data]);
    }


    /**
     * Menyimpan data hasil impor CSV ke database untuk **Siaga Tabel / Tabel Publikasi**.
     * 
     * Melakukan validasi terhadap isi data hasil pratinjau, mencatat log setiap proses penyimpanan,
     * dan mengabaikan baris yang tidak memiliki judul publikasi atau judul tabel.
     * Data disimpan ke tabel `siaga_tabel_data`.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function simpanFinal(Request $request)
    {
        Log::info('simpanFinal: Data yang diterima dari frontend:', ['data' => $request->all()]);

        $request->validate([
            'data' => 'required|array',
            'data.*.nomor_publikasi' => 'nullable|string|max:255',
            'data.*.judul_publikasi' => 'required|string|max:255',
            'data.*.nomor_tabel'     => 'nullable|string|max:255',
            'data.*.judul_tabel'     => 'required|string|max:255',
            'data.*.nomor_halaman'   => 'nullable|string|max:255',
        ]);

        $savedCount = 0;
        $failedRows = [];
        $inputData = $request->input('data', []);

        $validRows = collect($inputData)->filter(
            fn($row) =>
            !empty(trim($row['judul_publikasi'] ?? '')) && !empty(trim($row['judul_tabel'] ?? ''))
        )->count();

        if ($validRows === 0) {
            return redirect()->route('siaga.import.form')
                ->with('error', 'Tidak ada data valid untuk disimpan. Pastikan setidaknya ada satu baris dengan judul publikasi dan judul tabel.');
        }

        foreach ($inputData as $index => $row) {
            $nomor_publikasi = trim($row['nomor_publikasi'] ?? '');
            $judul_publikasi = trim($row['judul_publikasi'] ?? '');
            $nomor_tabel     = trim($row['nomor_tabel'] ?? '');
            $judul_tabel     = trim($row['judul_tabel'] ?? '');
            $nomor_halaman   = trim($row['nomor_halaman'] ?? '');

            if (empty($judul_publikasi) || empty($judul_tabel)) {
                Log::warning("simpanFinal: Melewatkan baris ke-$index karena judul kosong.", ['row_data' => $row]);
                $failedRows[] = ['index' => $index, 'reason' => 'Judul publikasi atau judul tabel kosong.'];
                continue;
            }

            try {
                SiagaTabelData::create([
                    'nomor_publikasi' => $nomor_publikasi,
                    'judul_publikasi' => $judul_publikasi,
                    'nomor_tabel'     => $nomor_tabel,
                    'judul_tabel'     => $judul_tabel,
                    'nomor_halaman'   => $nomor_halaman,
                    'pengolah_id'     => Auth::id(),
                    'status'          => 'belum ditugaskan',
                ]);
                $savedCount++;
            } catch (\Exception $e) {
                Log::error("simpanFinal: Gagal menyimpan baris ke-$index: " . $e->getMessage());
                $failedRows[] = ['index' => $index, 'reason' => $e->getMessage()];
            }
        }

        if ($savedCount > 0) {
            $message = "$savedCount baris data berhasil disimpan!";
            if (!empty($failedRows)) {
                $message .= " Namun, " . count($failedRows) . " baris gagal disimpan.";
            }
            return redirect()->route('siaga.import.form')->with('success', $message);
        } else {
            return redirect()->route('siaga.import.form')
                ->with('error', 'Tidak ada data valid yang dapat disimpan dari ' . count($inputData) . ' baris yang dikirim.');
        }
    }

    /**
     * Tampilkan halaman penugasan dan data siaga tabel (tabel publikasi).
     * 
     * Jika permintaan berasal dari AJAX (DataTables), kembalikan data tabel publikasi
     * yang dikelompokkan berdasarkan judul dan nomor publikasi.
     * Jika bukan AJAX, tampilkan halaman penugasan dengan daftar petugas PST.
     */
    public function halamanPenugasan(Request $request)
    {
        if ($request->ajax()) {
            $data = SiagaTabelData::select('judul_publikasi', 'nomor_publikasi', DB::raw('MAX(created_at) as created_at'))
                ->where('pengolah_id', Auth::id())
                ->where('status', '!=', 'rilis')
                ->groupBy('judul_publikasi', 'nomor_publikasi')
                ->orderByDesc('created_at');

            return DataTables::of($data)
                ->addColumn('judul_publikasi', function ($row) {
                    return '
                        <div class="flex items-center gap-x-2 max-w-[260px] text-sm" title="' . e($row->judul_publikasi) . '">
                            <span class="material-symbols-outlined text-blue-500 text-[20px]">book</span>
                            <span class="truncate hover:whitespace-normal hover:bg-white hover:shadow-lg transition-all duration-200 rounded px-1" style="max-width: 200px;">
                                ' . e($row->judul_publikasi) . '
                            </span>
                        </div>';
                })
                ->addColumn('status', function ($row) {
                    $total = SiagaTabelData::where('judul_publikasi', $row->judul_publikasi)->count();
                    $ditugaskan = SiagaTabelData::where('judul_publikasi', $row->judul_publikasi)
                        ->whereNotNull('petugas_pst_id')->count();

                    $baseClass = 'inline-flex items-center justify-between px-4 py-1 rounded-full text-sm font-medium border w-40 bg-white';

                    if ($ditugaskan === $total) {
                        return '<span class="' . $baseClass . ' border-green-300 text-green-700">
                            Sudah Ditugaskan
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                        </span>';
                    }

                    return '<span class="' . $baseClass . ' border-yellow-300 text-yellow-700">
                        Belum Ditugaskan
                        <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                    </span>';
                })
                ->addColumn('petugas_pst', function ($row) {
                    $first = SiagaTabelData::with('petugasPst')
                        ->where('judul_publikasi', $row->judul_publikasi)
                        ->whereNotNull('petugas_pst_id')
                        ->first();

                    if ($first && $first->petugasPst) {
                        return '
                            <div class="flex flex-col leading-tight">
                                <span class="font-medium text-gray-800 truncate max-w-[120px]" title="' . e($first->petugasPst->name) . '">
                                    ' . e($first->petugasPst->name) . '
                                </span>
                                <span class="text-xs text-gray-500 truncate max-w-[120px]" title="' . e($first->petugasPst->email) . '">
                                    ' . e($first->petugasPst->email) . '
                                </span>
                            </div>';
                    }

                    return '<span class="text-xs text-gray-400 italic">Belum Ditugaskan</span>';
                })
                ->addColumn('aksi', function ($row) {
                    $judul = e($row->judul_publikasi);
                    $total = SiagaTabelData::where('judul_publikasi', $judul)->count();
                    $ditugaskan = SiagaTabelData::where('judul_publikasi', $judul)
                        ->whereNotNull('petugas_pst_id')->count();

                    $btnBase = 'w-9 h-9 flex items-center justify-center rounded-full shadow-sm transition text-white';

                    $btnDetail = '
                        <button 
                            class="btn-detail ' . $btnBase . ' bg-gray-500 hover:bg-gray-700"
                            data-judul="' . $judul . '"
                            title="Lihat Detail">
                            <span class="material-symbols-outlined text-base">info</span>
                        </button>';

                    if ($ditugaskan === $total) {
                        $btnBatal = '
                            <button 
                                class="btn-batal ' . $btnBase . ' bg-red-600 hover:bg-red-800"
                                data-judul="' . $judul . '"
                                title="Batalkan Penugasan">
                                <span class="material-symbols-outlined text-base">cancel</span>
                            </button>';
                        return '<div class="flex items-center gap-2">' . $btnDetail . $btnBatal . '</div>';
                    }

                    $btnTugaskan = '
                        <button 
                            class="btn-modal ' . $btnBase . ' bg-blue-600 hover:bg-blue-800"
                            data-judul="' . $judul . '"
                            title="Tugaskan Petugas">
                            <span class="material-symbols-outlined text-base">assignment_ind</span>
                        </button>';
                    return '<div class="flex items-center gap-2">' . $btnDetail . $btnTugaskan . '</div>';
                })
                ->rawColumns(['judul_publikasi', 'status', 'petugas_pst', 'aksi'])
                ->make(true);
        }

        $petugasList = User::role('petugas_pst')->get();
        return view('siaga_tabel.penugasan', compact('petugasList'));
    }

    /**
     * Ambil detail tabel publikasi berdasarkan judul (untuk siaga tabel).
     * 
     * @param string $judul Judul publikasi yang ingin ditampilkan.
     * @return \Illuminate\Http\JsonResponse Daftar tabel dengan status dan link output-nya.
     */
    public function getDetail2($judul)
    {
        $data = SiagaTabelData::where('judul_publikasi', $judul)
            ->where('pengolah_id', Auth::id())
            ->get([
                'nomor_tabel',
                'judul_tabel',
                'nomor_halaman',
                'status',
                'link_output'
            ]);

        return response()->json($data);
    }

    /**
     * Proses penugasan petugas PST untuk satu publikasi (siaga tabel / tabel publikasi).
     * 
     * Validasi input, lalu perbarui setiap tabel dalam publikasi tersebut
     * agar memiliki `petugas_pst_id` dan status menjadi "ditugaskan".
     * 
     * @param Request $request berisi judul publikasi dan ID petugas PST
     * @return \Illuminate\Http\JsonResponse pesan status proses penugasan
     */
    public function prosesPenugasanPublikasi(Request $request)
    {
        $request->validate([
            'judul_publikasi' => 'required|string',
            'petugas_id' => 'required|exists:users,id',
        ]);

        $data = SiagaTabelData::where('judul_publikasi', $request->judul_publikasi)
            ->where('pengolah_id', Auth::id())
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        foreach ($data as $row) {
            $row->update([
                'petugas_pst_id' => $request->petugas_id,
                'status' => 'ditugaskan'
            ]);
        }

        return response()->json(['message' => 'Publikasi berhasil ditugaskan!']);
    }

    /**
     * Batalkan penugasan publikasi yang sebelumnya sudah ditugaskan ke petugas PST.
     * 
     * Menghapus `petugas_pst_id` dan mengembalikan status menjadi "belum ditugaskan".
     * 
     * @param Request $request berisi judul publikasi yang akan dibatalkan
     * @return \Illuminate\Http\JsonResponse pesan status pembatalan
     */
    public function batalkanPenugasan(Request $request)
    {
        $request->validate([
            'judul_publikasi' => 'required|string'
        ]);

        $data = SiagaTabelData::where('judul_publikasi', $request->judul_publikasi)
            ->where('pengolah_id', Auth::id())
            ->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        foreach ($data as $row) {
            $row->update([
                'petugas_pst_id' => null,
                'status' => 'belum ditugaskan'
            ]);
        }

        return response()->json(['message' => 'Penugasan berhasil dibatalkan!']);
    }


    // Petugas PST

    /**
     * Tampilkan halaman utama untuk petugas PST (siaga tabel / tabel publikasi).
     * 
     * Halaman ini berisi daftar publikasi yang ditugaskan kepada petugas PST.
     * 
     * @return \Illuminate\View\View tampilan halaman link hasil
     */
    public function halamanPst()
    {
        return view('siaga_tabel.link_hasil');
    }

    /**
     * Ambil data publikasi yang ditugaskan untuk petugas PST.
     * 
     * Menghasilkan data dalam format DataTables, termasuk kolom status,
     * nama pengolah, serta tombol aksi (detail, upload/edit link, lihat link).
     * 
     * @param Request $request permintaan AJAX dari DataTables
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View daftar publikasi dalam bentuk JSON
     */
    public function dataUntukPst(Request $request)
    {
        if ($request->ajax()) {
            $data = SiagaTabelData::select('judul_publikasi')
                ->where('petugas_pst_id', Auth::id())
                ->groupBy('judul_publikasi')
                ->orderBy('judul_publikasi')
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()

                // Kolom nama pengolah
                ->addColumn('pengolah', function ($row) {
                    $pengolahName = SiagaTabelData::where('judul_publikasi', $row->judul_publikasi)
                        ->whereNotNull('pengolah_id')
                        ->with('pengolah')
                        ->first()?->pengolah?->name;

                    return $pengolahName
                        ? '<span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-sm">' . e($pengolahName) . '</span>'
                        : '<span class="italic text-gray-400">Belum ditugaskan</span>';
                })

                // Kolom status publikasi
                ->addColumn('status_label', function ($row) {
                    $total = SiagaTabelData::where('judul_publikasi', $row->judul_publikasi)->count();
                    $rilis = SiagaTabelData::where('judul_publikasi', $row->judul_publikasi)
                        ->where('status', 'rilis')->count();

                    $warna = ($rilis === $total) ? 'bg-green-600' : 'bg-yellow-500';
                    $label = ($rilis === $total) ? 'Rilis Lengkap' : 'Belum Lengkap';

                    return '<span class="inline-block px-2 py-1 text-xs font-semibold text-white rounded ' . $warna . '">' . $label . '</span>';
                })

                // Kolom aksi dengan tombol (detail, upload/edit, lihat link)
                ->addColumn('aksi', function ($row) {
                    $judul = e($row->judul_publikasi);
                    $link = SiagaTabelData::where('judul_publikasi', $judul)
                        ->where('petugas_pst_id', Auth::id())
                        ->value('link_output');

                    $total = SiagaTabelData::where('judul_publikasi', $judul)->count();
                    $rilis = SiagaTabelData::where('judul_publikasi', $judul)
                        ->where('status', 'rilis')->count();

                    // Tombol-tombol utama
                    $btnDetail = '
                        <button class="btn-detail material-symbols-outlined text-white rounded-full p-2 bg-blue-500 hover:bg-blue-600 transition"
                            data-judul="' . $judul . '" title="Detail">
                            info
                        </button>';

                    $btnUpload = ($rilis === $total)
                        ? '<button class="btn-edit material-symbols-outlined text-white rounded-full p-2 bg-yellow-500 hover:bg-yellow-600 transition"
                            data-judul="' . $judul . '" data-link="' . e($link) . '" title="Edit Link">
                            edit
                        </button>'
                        : '<button class="btn-upload material-symbols-outlined text-white rounded-full p-2 bg-green-500 hover:bg-green-600 transition"
                            data-judul="' . $judul . '" title="Upload">
                            upload
                        </button>';

                    $btnLihat = $link
                        ? '<a href="' . e($link) . '" target="_blank"
                            class="material-symbols-outlined text-white rounded-full p-2 bg-purple-500 hover:bg-purple-600 transition"
                            title="Lihat Link">
                            link
                        </a>'
                        : '';

                    return '
                        <div class="flex flex-wrap gap-2">
                            ' . $btnDetail . '
                            ' . $btnUpload . '
                            ' . $btnLihat . '
                        </div>';
                })

                ->rawColumns(['status_label', 'aksi', 'pengolah'])
                ->make(true);
        }
    }

    /**
     * Menampilkan detail tabel berdasarkan judul publikasi untuk petugas PST yang sedang login.
     * 
     * @param string $judul Judul publikasi.
     * @return \Illuminate\Http\JsonResponse Data tabel dalam format JSON.
     */
    public function getDetail($judul)
    {
        $data = SiagaTabelData::where('judul_publikasi', $judul)
            ->where('petugas_pst_id', Auth::id())
            ->get([
                'nomor_tabel',
                'judul_tabel',
                'nomor_halaman',
                'status',
                'link_output'
            ]);

        return response()->json($data);
    }

    /**
     * Mengunggah atau memperbarui link hasil publikasi siaga tabel.
     * 
     * @param \Illuminate\Http\Request $request Berisi judul publikasi dan link.
     * @return \Illuminate\Http\JsonResponse Pesan sukses setelah link diunggah.
     */
    public function uploadLink(Request $request)
    {
        $request->validate([
            'judul_publikasi' => 'required|string',
            'link' => 'required|url'
        ]);

        $dataList = SiagaTabelData::where('judul_publikasi', $request->judul_publikasi)
            ->where('petugas_pst_id', Auth::id())
            ->get();

        foreach ($dataList as $data) {
            $data->update([
                'link_output' => $request->link,
                'status' => 'rilis'
            ]);
        }

        return response()->json(['message' => 'Link berhasil diupload untuk seluruh tabel publikasi!']);
    }

    /**
     * Menampilkan data tabel publikasi yang sudah berstatus rilis ke halaman portal data.
     * 
     * @param \Illuminate\Http\Request $request Opsional: parameter pencarian.
     * @return \Yajra\DataTables\DataTableResponse Data publikasi dalam format DataTables.
     */
    public function portalData(Request $request)
    {
        $query = SiagaTabelData::where('status', 'rilis')
            ->when($request->search, fn($q) => $q->where('judul_tabel', 'like', '%' . $request->search . '%'));

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('link_output', function ($row) {
                return $row->link_output
                    ? '<a href="' . $row->link_output . '" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-700 text-white text-sm font-semibold rounded-lg hover:bg-blue-800 transition-all duration-200 shadow-sm hover:shadow-md">Unduh</a>'
                    : '<span class="text-gray-400 italic">Tidak tersedia</span>';
            })
            ->rawColumns(['link_output'])
            ->make(true);
    }
}
