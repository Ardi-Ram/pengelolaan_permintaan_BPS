<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\HasilOlahan;
use App\Models\CategoryData;
use Illuminate\Http\Request;
use App\Models\PermintaanData;
use Yajra\DataTables\DataTables;
use App\Models\PermintaanDataRutin;
use Illuminate\Support\Facades\Log;
use App\Mail\PermintaanDataDiproses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class PengolahController extends \Illuminate\Routing\Controller
{

    /**
     * Menampilkan halaman utama pengolah.
     * 
     * Berisi daftar kategori data yang tersedia untuk dipilih.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $kategoriList = CategoryData::all();
        return view('pengolah.index', compact('kategoriList'));
    }

    /**
     * Menampilkan dashboard pengolah data.
     * 
     * Dashboard berisi ringkasan jumlah permintaan olah data berdasarkan status,
     * total permintaan, dan 5 permintaan terbaru yang ditangani.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $userId = Auth::id();

        // Hitung jumlah permintaan berdasarkan status
        $antrian = PermintaanData::where('pengolah_id', $userId)
            ->where('status', 'antrian')
            ->count();

        $proses = PermintaanData::where('pengolah_id', $userId)
            ->where('status', 'proses')
            ->count();

        $selesai = PermintaanData::where('pengolah_id', $userId)
            ->where('status', 'selesai')
            ->count();

        $total = PermintaanData::where('pengolah_id', $userId)->count();

        // Ambil 5 permintaan terbaru
        $latestRequests = PermintaanData::with(['kategori', 'pemilikData'])
            ->where('pengolah_id', $userId)
            ->latest()
            ->limit(5)
            ->get();

        return view('pengolah.dashboard', compact(
            'antrian',
            'proses',
            'selesai',
            'total',
            'latestRequests'
        ));
    }
    /**
     * Mengambil data permintaan olah data untuk pengolah yang sedang login.
     * 
     * Data yang diambil mencakup permintaan dengan status "antrian" dan "proses"
     * serta menampilkan informasi kategori, petugas PST, dan hasil olahan.
     * 
     * Data ditampilkan menggunakan DataTables dengan fitur pencarian, filter kategori,
     * dan berbagai aksi seperti upload hasil olahan, melihat deskripsi, atau menolak tugas.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function getData(Request $request)
    {
        $userId = Auth::id();

        $data = PermintaanData::with(['kategori', 'petugasPst', 'hasilOlahan'])
            ->where(function ($query) use ($userId) {
                $query->where(function ($q) use ($userId) {
                    $q->whereIn('status', ['antrian', 'proses'])
                        ->where('pengolah_id', $userId)
                        ->whereNull('alasan');
                })->orWhere(function ($q) use ($userId) {
                    $q->where('status', 'proses')
                        ->where('pengolah_id', $userId)
                        ->whereNotNull('alasan');
                });
            })
            ->when($request->filled('kategori'), function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori);
            })
            ->orderBy('created_at', 'desc');

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                if ($request->has('search') && $search = $request->input('search.value')) {
                    $query->whereHas('pemilikData', function ($q) use ($search) {
                        $q->where('kode_transaksi', 'like', "%{$search}%");
                    })->orWhere('judul_permintaan', 'like', "%{$search}%");
                }
            })
            ->addIndexColumn()
            ->addColumn('judul_permintaan', function ($row) {
                return '
                <div class="flex items-center gap-2 text-sm text-gray-700" title="' . e($row->judul_permintaan) . '">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                    <span class="truncate max-w-[200px] hover:whitespace-normal hover:bg-white hover:shadow px-1 rounded transition">'
                    . e($row->judul_permintaan) . '
                    </span>
                </div>';
            })
            ->addColumn('kode_transaksi', fn($row) => $row->pemilikData->kode_transaksi ?? '-')
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')
            ->addColumn('petugas_pst', function ($row) {
                return '
                <div class="grid grid-cols-[40px_auto] items-center gap-2">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white font-bold text-sm">'
                    . strtoupper(implode('', array_slice(array_map(fn($w) => mb_substr($w, 0, 1), preg_split('/\s+/', trim($row->petugasPst?->name ?? ''))), 0, 3))) . '
                    </div>
                    <div class="flex flex-col leading-tight">
                        <span class="font-medium text-gray-800 truncate max-w-[120px]" title="' . e($row->petugasPst?->name ?? '-') . '">'
                    . e($row->petugasPst?->name ?? '-') . '
                        </span>
                        <span class="text-xs text-gray-500 truncate max-w-[120px]" title="' . e($row->petugasPst?->email ?? '-') . '">'
                    . e($row->petugasPst?->email ?? '-') . '
                        </span>
                    </div>
                </div>';
            })
            ->addColumn('created_at', fn($row) => \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y'))
            ->addColumn('status', function ($row) {
                $badgeBase = 'inline-flex items-center justify-between px-4 py-1 rounded-full text-sm font-medium border w-28 bg-white';
                if ($row->status === 'proses' && !is_null($row->hasilOlahan?->catatan_verifikasi)) {
                    return '<span class="' . $badgeBase . ' border-red-300 text-red-700">Invalid
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span></span>';
                } elseif ($row->status === 'proses') {
                    return '<span class="' . $badgeBase . ' border-yellow-300 text-yellow-700">Proses
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span></span>';
                } elseif ($row->status === 'antrian') {
                    return '<span class="' . $badgeBase . ' border-gray-300 text-gray-600">Antrian
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span></span>';
                }
                return '<span class="' . $badgeBase . ' border-gray-200 text-gray-400 italic">Tidak Dikenal
                <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span></span>';
            })
            ->addColumn('aksi', function ($row) {
                $btnBase = 'w-9 h-9 flex items-center justify-center rounded-full shadow-sm transition';
                if ($row->status === 'proses') {
                    $buttons = '
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="openUploadModal(' . $row->id . ')" 
                            class="' . $btnBase . ' bg-yellow-400 hover:bg-yellow-600 text-white" title="Upload Data">
                            <span class="material-icons-outlined text-base leading-none">upload</span>
                        </button>
                        <button type="button" onclick="openDeskripsiModal(\'' . e($row->deskripsi) . '\')" 
                            class="' . $btnBase . ' bg-blue-600 hover:bg-blue-800 text-white" title="Lihat Deskripsi">
                            <span class="material-icons-outlined text-base leading-none">info</span>
                        </button>';
                    if (!is_null($row->hasilOlahan?->catatan_verifikasi)) {
                        $catatan_verifikasi = e($row->hasilOlahan->catatan_verifikasi);
                        $buttons .= '
                        <button type="button" onclick="openAlasanModal(`' . $catatan_verifikasi . '`)" 
                            class="' . $btnBase . ' bg-red-600 hover:bg-red-800 text-white" title="Lihat Alasan Verifikasi">
                            <span class="material-icons-outlined text-base leading-none">report</span>
                        </button>';
                    }
                    $buttons .= '</div>';
                    return $buttons;
                }

                return '
                <div class="flex items-center gap-2">
                    <form method="POST" action="' . route('pengolah.permintaan.apply', $row->id) . '" class="inline-block">'
                    . csrf_field() . '
                        <button type="submit" class="' . $btnBase . ' bg-green-500 hover:bg-green-700 text-green-100"
                            title="Terima Tugas">
                            <span class="material-symbols-outlined text-base leading-none">check_circle</span>
                        </button>
                    </form>
                    <button type="button" onclick="openRejectModal(' . $row->id . ')" 
                        class="' . $btnBase . ' bg-red-500 hover:bg-red-600 text-red-100" title="Tolak Tugas">
                        <span class="material-symbols-outlined text-base leading-none">cancel</span>
                    </button>
                    <button type="button" onclick="openDeskripsiModal(\'' . e($row->deskripsi) . '\')" 
                        class="' . $btnBase . ' bg-blue-500 hover:bg-blue-600 text-blue-100" title="Lihat Deskripsi">
                        <span class="material-symbols-outlined text-base leading-none">info</span>
                    </button>
                </div>';
            })
            ->rawColumns(['aksi', 'petugas_pst', 'judul_permintaan', 'status', 'kategori'])
            ->make(true);
    }

    /**
     * Menolak permintaan olah data yang masih berstatus "antrian".
     * 
     * Pengolah wajib mengisi alasan penolakan sebelum data dikembalikan.
     * Hanya pengolah yang terkait dan berstatus "antrian" yang dapat menolak.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan' => 'required|string|max:1000',
        ]);

        $data = PermintaanData::with('pemilikData')->findOrFail($id);

        if ($data->pengolah_id === Auth::id() && $data->status === 'antrian') {
            $data->update(['alasan' => $request->alasan]);
            return redirect()->back()->with('success', 'Permintaan berhasil ditolak dan dikembalikan.');
        }

        return redirect()->back()->with('error', 'Gagal menolak permintaan ini.');
    }

    /**
     * Apply (menerima) permintaan olah data oleh pengolah.
     * Mengubah status dari "antrian" ke "proses" dan mengirim notifikasi ke pemilik data.
     */
    public function apply($id)
    {
        // Ambil data permintaan beserta relasinya
        $data = PermintaanData::with(['pemilikData', 'pengolah'])->findOrFail($id);

        // Pastikan pengolah sesuai dan status masih antrian
        if ($data->pengolah_id === Auth::id() && $data->status === 'antrian') {
            $data->status = 'proses';
            $data->save();

            // Kirim email notifikasi ke pemilik data
            if ($data->pemilikData && $data->pemilikData->email) {
                Mail::to($data->pemilikData->email)
                    ->send(new PermintaanDataDiproses($data));
            }

            return redirect()->route('pengolah.index')->with('success', 'Permintaan berhasil di-apply.');
        }

        return redirect()->back()->with('error', 'Tidak dapat meng-apply permintaan ini.');
    }


    /**
     * Upload hasil olahan data dari pengolah.
     * Menyimpan file ke folder sementara dan memperbarui status permintaan menjadi "selesai".
     */
    public function upload(Request $request, $id)
    {
        // Validasi file upload
        $request->validate([
            'file_hasil' => 'required|file|max:10240|mimes:xls,xlsx,csv',
        ]);

        $data = PermintaanData::with('pemilikData')->findOrFail($id);

        // Hanya pengolah yang sedang memproses data yang boleh upload
        if ($data->pengolah_id !== Auth::id() || $data->status !== 'proses') {
            return back()->with('error', 'Tidak dapat upload untuk permintaan ini.');
        }

        if ($request->hasFile('file_hasil')) {
            $file = $request->file('file_hasil');

            // Simpan file sementara dengan nama unik
            $fileName = 'TEMP_' . now()->format('Ymd_His') . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $path = $file->storeAs('temp_uploads', $fileName, 'public');

            // Jika sudah ada hasil sebelumnya, update. Kalau belum, buat baru.
            $existing = HasilOlahan::where('permintaan_data_id', $data->id)->first();
            $existing
                ? $existing->update(['nama_file' => $fileName, 'path_file' => $path])
                : HasilOlahan::create(['permintaan_data_id' => $data->id, 'nama_file' => $fileName, 'path_file' => $path]);

            // Update status permintaan jadi selesai
            $data->update(['status' => 'selesai']);

            return back()->with('success', 'File berhasil diupload.');
        }

        return back()->with('error', 'Gagal mengupload file.');
    }
}
