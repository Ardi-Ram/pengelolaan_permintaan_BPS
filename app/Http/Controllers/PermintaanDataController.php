<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subject;
use App\Models\ActivityLog;
use App\Models\PemilikData;
use App\Models\CategoryData;
use Illuminate\Http\Request;
use App\Models\PermintaanData;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\PerantaraPermintaan;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PenugasanBaruNotification;

class PermintaanDataController extends  \Illuminate\Routing\Controller
{
    /**
     * Menerapkan middleware untuk membatasi akses hanya bagi pengguna dengan role "petugas_pst".
     *
     * Middleware ini memastikan bahwa hanya petugas PST yang dapat mengakses
     * semua method dalam controller ini.
     */
    public function __construct()
    {
        $this->middleware('role:petugas_pst');
    }

    /**
     * Menampilkan halaman form untuk membuat permintaan olah data baru.
     *
     * Mengambil data kategori, subject, dan perantara untuk ditampilkan pada form input.
     *
     * @return \Illuminate\View\View  Mengembalikan view `permintaanolahdata.form` beserta data terkait.
     */
    public function create()
    {
        $kategoriData = DB::table('category_data')->get();
        $subjectData = Subject::select('id', 'category_data_id', 'nama_subject')->get();
        $perantaraData = PerantaraPermintaan::all(); // Ambil data perantara

        return view('permintaanolahdata.form', compact('kategoriData', 'subjectData', 'perantaraData'));
    }

    /**
     * Menyimpan data permintaan olah data baru ke dalam database.
     *
     * Method ini melakukan validasi input pengguna, membuat entri baru untuk pemilik data,
     * dan menyimpan satu atau lebih permintaan olah data berdasarkan input array.
     * Semua operasi dilakukan dalam transaksi database untuk menjaga konsistensi data.
     *
     * @param  \Illuminate\Http\Request  $request  Data permintaan yang dikirim dari form.
     * @return \Illuminate\Http\RedirectResponse   Redirect kembali ke halaman sebelumnya
     *                                             dengan pesan sukses atau error.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_wa' => 'required|string|max:20',
            'perantara_id' => 'required|integer|exists:perantara_data,id',
            'jumlah_data' => 'required|integer|min:1',

            // Validasi array data
            'nama_data' => 'required|array|min:1',
            'nama_data.*' => 'required|string|max:255',
            'kategori_id' => 'required|array|min:1',
            'kategori_id.*' => 'required|integer|exists:category_data,id',
            'subject_id' => 'required|array|min:1',
            'subject_id.*' => 'required|integer|exists:subjects,id',
            'deskripsi' => 'required|array|min:1',
            'deskripsi.*' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $pemilik = PemilikData::create([
                'nama_pemilik' => $request->nama,
                'instansi' => $request->instansi,
                'email' => $request->email,
                'no_wa' => $request->no_wa,
                'kode_transaksi' => 'TRX-' . time(),
                'perantara_id' => $request->perantara_id
            ]);

            for ($i = 0; $i < count($request->nama_data); $i++) {
                PermintaanData::create([
                    'pemilik_data_id' => $pemilik->id,
                    'judul_permintaan' => $request->nama_data[$i],
                    'deskripsi' => $request->deskripsi[$i],
                    'kategori_id' => $request->kategori_id[$i],
                    'subject_id' => $request->subject_id[$i],
                    'status' => 'antrian',
                    'petugas_pst_id' => Auth::user()->id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Permintaan data berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan dalam penyimpanan data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman penugasan olah data.
     *
     * Method ini mengambil daftar pengguna dengan role `pengolah_data` serta
     * seluruh kategori data, lalu menampilkannya pada halaman `permintaanolahdata.penugasan`.
     *
     * @return \Illuminate\View\View  Mengembalikan view berisi daftar pengolah dan kategori data.
     */
    public function index()
    {
        $pengolahList = User::role('pengolah_data')->get();
        $kategoriList = CategoryData::all();

        return view('permintaanolahdata.penugasan', compact('pengolahList', 'kategoriList'));
    }

    /**
     * Mengambil data permintaan olah data untuk ditampilkan di DataTables (AJAX).
     *
     * Method ini menampilkan daftar permintaan yang ditangani oleh Petugas PST,
     * dengan filter berdasarkan kategori, status, dan pencarian bebas.  
     * Hasil dikembalikan dalam format JSON sesuai kebutuhan Yajra DataTables.
     *
     * @param  \Illuminate\Http\Request  $request  Permintaan AJAX dari DataTables, berisi parameter filter & pencarian.
     * @return \Illuminate\Http\JsonResponse|null  Data JSON untuk DataTables, atau null jika bukan permintaan AJAX.
     */
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = PermintaanData::with(['kategori', 'pemilikData', 'subject', 'pengolah'])
                ->where('petugas_pst_id', Auth::id())
                ->where(function ($q) {
                    $q->where(function ($sub) {
                        $sub->whereNull('pengolah_id')->whereNull('alasan');
                    })->orWhere(function ($sub) {
                        $sub->whereNotNull('pengolah_id')->whereNotNull('alasan');
                    });
                })
                ->when($request->kategori, function ($q) use ($request) {
                    $q->where('kategori_id', $request->kategori);
                })
                ->when($request->status, function ($q) use ($request) {
                    $status = strtolower($request->status);
                    if ($status === 'draf') {
                        $q->whereNull('pengolah_id')->whereNull('alasan');
                    } elseif ($status === 'ditolak') {
                        $q->whereNotNull('pengolah_id')->whereNotNull('alasan');
                    }
                })
                ->latest();

            return DataTables::of($query)
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $search = $request->input('search.value')) {
                        $query->where(function ($q) use ($search) {
                            $q->where('judul_permintaan', 'like', "%{$search}%")
                                ->orWhereHas('pemilikData', fn($q2) => $q2->where('kode_transaksi', 'like', "%{$search}%"))
                                ->orWhereHas('subject', fn($q3) => $q3->where('nama_subject', 'like', "%{$search}%"));
                        });
                    }
                })
                ->addIndexColumn()
                ->addColumn('judul_permintaan', function ($row) {
                    return '
                <div class="flex items-center gap-2 max-w-[250px] text-sm text-gray-700" title="' . e($row->judul_permintaan) . '">
                    <span class="material-symbols-outlined text-blue-500 text-sm">description</span>
                    <span class="truncate max-w-[200px] hover:whitespace-normal hover:bg-white hover:shadow px-1 rounded transition">
                        ' . e($row->judul_permintaan) . '
                    </span>
                </div>';
                })
                ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')
                ->addColumn('status', function ($row) {
                    $baseClass = 'inline-flex items-center justify-between px-4 py-1 rounded-full text-sm font-medium border w-28 bg-white';

                    if (is_null($row->pengolah_id) && is_null($row->alasan)) {
                        return '<span class="' . $baseClass . ' border-gray-300 text-gray-700">
                        Draf
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span>
                    </span>';
                    } elseif (!is_null($row->pengolah_id) && $row->alasan) {
                        return '<span class="' . $baseClass . ' border-gray-300 text-gray-700">
                        Ditolak
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                    </span>';
                    }

                    return '<span class="' . $baseClass . ' border-gray-300 text-gray-500 italic">
                    Tidak Dikenal
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                </span>';
                })
                ->addColumn('kode_transaksi', function ($row) {
                    $kode = $row->pemilikData->kode_transaksi ?? '-';
                    if ($kode === '-') return $kode;

                    return '
                    <div class="flex items-center gap-2">
                        <span class="text-gray-800 text-sm" id="kode-' . $row->id . '">' . $kode . '</span>
                        <button onclick="salinKode(\'kode-' . $row->id . '\', this)" class="text-blue-600 hover:text-blue-800 text-xs">
                            <span class="material-symbols-outlined align-middle" style="font-size: 16px;">content_copy</span>
                        </button>
                    </div>';
                })
                ->addColumn('created_at', fn($row) => $row->created_at->format('d M Y H:i'))
                ->addColumn('action', function ($row) {
                    $editUrl = route('permintaan.edit', $row->id);
                    $btnClass = 'w-9 h-9 flex items-center justify-center rounded-full shadow-sm transition';
                    $buttons = '<div class="flex gap-2">';

                    // Tombol Edit
                    $buttons .= '
                    <a href="' . $editUrl . '" class="' . $btnClass . ' bg-yellow-400 text-white hover:bg-yellow-600" title="Edit">
                        <span class="material-icons-outlined text-base">edit</span>
                    </a>';

                    // Tombol Tugaskan
                    $buttons .= '
                    <button class="btn-penugasan ' . $btnClass . ' bg-blue-600 text-white hover:bg-blue-800"
                        data-id="' . $row->id . '" data-nama="' . e($row->judul_permintaan) . '" title="Tugaskan">
                        <span class="material-icons-outlined text-base">person_add</span>
                    </button>';

                    // Tombol Alasan (jika ada penolakan)
                    if (!is_null($row->alasan)) {
                        $namaPengolah = $row->pengolah?->name ?? 'Pengolah Tidak Diketahui';
                        $buttons .= '
                        <button class="btn-alasan ' . $btnClass . ' bg-red-600 text-white hover:bg-red-800"
                            data-alasan="' . e($row->alasan) . '" data-pengolah="' . e($namaPengolah) . '" title="Lihat alasan penolakan">
                            <span class="material-icons-outlined text-base">info</span>
                        </button>';
                    }

                    $buttons .= '</div>';
                    return $buttons;
                })
                ->rawColumns(['action', 'judul_permintaan', 'status', 'kode_transaksi'])
                ->make(true);
        }
    }

    /**
     * Menyimpan data penugasan permintaan olah data kepada pengolah tertentu.
     *
     * Method ini memvalidasi input, memperbarui relasi pengolah pada permintaan data,
     * menghapus alasan penolakan sebelumnya (jika ada), dan mengirim notifikasi
     * ke pengguna pengolah yang ditugaskan.
     *
     * @param  \Illuminate\Http\Request  $request  Data penugasan yang berisi ID permintaan dan ID pengolah.
     * @return \Illuminate\Http\RedirectResponse   Redirect dengan pesan sukses setelah penugasan tersimpan.
     */
    public function simpanPenugasan(Request $request)
    {
        $request->validate([
            'permintaan_id' => 'required|exists:permintaan_data,id',
            'pengolah_id' => 'required|exists:users,id',
        ]);

        $permintaan = PermintaanData::find($request->permintaan_id);
        $permintaan->pengolah_id = $request->pengolah_id;
        $permintaan->alasan = null; // Reset alasan penolakan jika ada
        $permintaan->save();

        $penugas = Auth::user();
        $pengolah = User::find($request->pengolah_id);
        $pengolah->notify(new PenugasanBaruNotification($permintaan));

        return redirect()->back()->with('success', 'Penugasan berhasil disimpan.');
    }

    /**
     * Membatalkan penugasan pengolah pada suatu permintaan olah data.
     *
     * Method ini digunakan untuk menghapus relasi antara permintaan data
     * dengan pengolah yang telah ditugaskan, **selama status masih "antrian"**.
     * Jika berhasil, akan mengembalikan respons JSON dengan status sukses.
     *
     * @param  int  $id  ID permintaan olah data yang ingin dibatalkan penugasannya.
     * @return \Illuminate\Http\JsonResponse  Respons JSON berisi status dan pesan hasil pembatalan.
     */
    public function batalPenugasan($id)
    {
        $permintaan = PermintaanData::findOrFail($id);

        if ($permintaan->status === 'antrian' && $permintaan->pengolah_id !== null) {
            $permintaan->pengolah_id = null;
            $permintaan->save();

            return response()->json(['success' => true, 'message' => 'Penugasan berhasil dibatalkan.']);
        }

        return response()->json(['success' => false, 'message' => 'Penugasan tidak bisa dibatalkan.']);
    }

    /**
     * Menampilkan halaman form edit untuk permintaan olah data tertentu.
     *
     * Method ini memuat data permintaan beserta relasinya (subject & kategori),
     * serta mengambil daftar perantara, kategori, dan subject (dikelompokkan per kategori)
     * untuk memudahkan pengisian ulang data oleh Petugas PST.
     *
     * @param  int  $id  ID permintaan olah data yang akan diedit.
     * @return \Illuminate\View\View  View `permintaanolahdata.edit` beserta data pendukungnya.
     */
    public function edit($id)
    {
        $permintaan = PermintaanData::with('subject', 'kategori')->findOrFail($id);

        $perantaraData = PerantaraPermintaan::all();
        $kategoriData = DB::table('category_data')->get();

        // Kelompokkan subject berdasarkan kategori untuk kebutuhan JS dinamis di form edit
        $subjectMap = Subject::all()
            ->groupBy('category_data_id')
            ->map(function ($items) {
                return $items->map(fn($s) => [
                    'id' => $s->id,
                    'nama' => $s->nama_subject,
                ]);
            });

        return view('permintaanolahdata.edit', compact(
            'permintaan',
            'perantaraData',
            'kategoriData',
            'subjectMap'
        ));
    }

    /**
     * Memperbarui data permintaan olah data beserta informasi pemiliknya.
     *
     * Method ini akan memvalidasi data masukan dari form edit, kemudian:
     * - Memastikan permintaan **belum ditugaskan** ke pengolah (tidak bisa diedit jika sudah ada `pengolah_id`).
     * - Memperbarui informasi pemilik (nama, instansi, email, dan nomor WA).
     * - Memperbarui detail permintaan (judul, kategori, subject, dan deskripsi).
     *
     * @param  \Illuminate\Http\Request  $request  Data permintaan dari form edit.
     * @param  int  $id  ID permintaan olah data yang akan diperbarui.
     * @return \Illuminate\Http\RedirectResponse  Redirect ke halaman penugasan dengan pesan hasil.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_wa' => 'required|string|max:20',
            'judul_permintaan' => 'required|string|max:255',
            'kategori_id' => 'required|integer|exists:category_data,id',
            'subject_id' => 'required|integer|exists:subjects,id',
            'deskripsi' => 'required|string',
        ]);

        $permintaan = PermintaanData::with('pemilikData')->findOrFail($id);

        // Tidak bisa diedit jika sudah ditugaskan ke pengolah
        if (!is_null($permintaan->pengolah_id)) {
            return redirect()->back()->with('error', 'Permintaan sudah ditugaskan dan tidak dapat diedit.');
        }

        // Update data pemilik
        $pemilik = $permintaan->pemilikData;
        $pemilik->update([
            'nama_pemilik' => $request->nama,
            'instansi' => $request->instansi,
            'email' => $request->email,
            'no_wa' => $request->no_wa,
        ]);

        // Update data permintaan
        $permintaan->update([
            'judul_permintaan' => $request->judul_permintaan,
            'kategori_id' => $request->kategori_id,
            'subject_id' => $request->subject_id,
            'deskripsi' => $request->deskripsi,
            'alasan' => $request->alasan,
        ]);

        return redirect()->route('permintaanolahdata.tugas')->with('success', 'Permintaan berhasil diperbarui!');
    }

    /**
     * Menampilkan halaman status permintaan olah data.
     *
     * Method ini mengambil daftar kategori dan pengolah data
     * untuk digunakan dalam filter atau tampilan status permintaan di view.
     *
     * @return \Illuminate\View\View  View `permintaanolahdata.status` dengan data pendukungnya.
     */
    public function statusData()
    {
        $kategoriList = CategoryData::all();
        $pengolahList = User::role('pengolah_data')->get();

        return view('permintaanolahdata.status', compact('kategoriList', 'pengolahList'));
    }

    /**
     * Mengambil data status permintaan olah data untuk ditampilkan di DataTables.
     *
     * Method ini menampilkan daftar permintaan yang:
     * - Sudah memiliki pengolah (`pengolah_id` tidak null)
     * - Ditugaskan oleh petugas PST yang sedang login
     * - Memiliki status `antrian`, `proses`, atau `selesai` yang belum diverifikasi
     *
     * Data akan diformat untuk keperluan tampilan DataTables, lengkap dengan:
     * - Kolom tambahan: `judul_permintaan`, `kategori`, `kode_transaksi`, `status`, `pengolah`, dan `action`
     * - Filter berdasarkan kategori, status, pengolah, atau pencarian bebas
     *
     * @param  \Illuminate\Http\Request  $request  Data filter atau pencarian dari frontend (AJAX DataTables)
     * @return \Yajra\DataTables\DataTableAbstract  Data dalam format JSON untuk DataTables
     */
    public function getStatusData(Request $request)
    {
        $data = PermintaanData::whereNotNull('pengolah_id')
            ->where('petugas_pst_id', Auth::id())
            ->where(function ($q) {
                $q->whereIn('status', ['antrian', 'proses'])
                    ->orWhere(function ($q) {
                        $q->where('status', 'selesai')
                            ->whereHas('hasilOlahan', function ($qq) {
                                $qq->whereNull('verifikasi_hasil')
                                    ->orWhere('verifikasi_hasil', false);
                            });
                    });
            })
            ->with(['pengolah', 'kategori', 'pemilikData', 'hasilOlahan'])
            ->orderBy('created_at', 'desc');

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                // 🔍 Filter pencarian umum
                if ($request->has('search') && $search = $request->input('search.value')) {
                    $query->where(function ($query) use ($search) {
                        $query->whereHas('pemilikData', fn($q) => $q->where('kode_transaksi', 'like', "%{$search}%"))
                            ->orWhere('judul_permintaan', 'like', "%{$search}%")
                            ->orWhere('status', 'like', "%{$search}%");
                    });
                }

                // 🔧 Filter berdasarkan dropdown
                if ($request->filled('kategori')) {
                    $query->where('kategori_id', $request->kategori);
                }
                if ($request->filled('status')) {
                    $query->where('status', $request->status);
                }
                if ($request->filled('pengolah')) {
                    $query->where('pengolah_id', $request->pengolah);
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
            ->addColumn('kode_transaksi', function ($row) {
                $kode = $row->pemilikData->kode_transaksi ?? '-';
                if ($kode === '-') return $kode;

                return '
                <div class="flex items-center gap-2">
                    <span class="text-gray-800 text-sm" id="kode-' . $row->id . '">' . $kode . '</span>
                    <button onclick="salinKode(\'kode-' . $row->id . '\', this)" class="text-blue-600 hover:text-blue-800 text-xs">
                        <span class="material-symbols-outlined align-middle" style="font-size: 16px;">content_copy</span>
                    </button>
                </div>';
            })
            ->editColumn('status', function ($row) {
                $status = strtolower($row->status);
                $colorClass = match ($status) {
                    'antrian' => 'bg-red-500',
                    'proses' => 'bg-yellow-500',
                    'selesai' => 'bg-green-500',
                    default => 'bg-gray-400',
                };

                return '
                <span class="inline-flex items-center justify-between px-3 py-1 rounded-full text-sm font-medium bg-white border border-gray-300 min-w-[6rem] max-w-[8rem]">
                    <span class="truncate">' . ucfirst($status) . '</span>
                    <span class="w-2.5 h-2.5 rounded-full ' . $colorClass . ' ml-2"></span>
                </span>';
            })
            ->addColumn('pengolah', function ($row) {
                if (!$row->pengolah) return '-';

                $nama = $row->pengolah->name;
                $email = $row->pengolah->email;
                $inisial = collect(explode(' ', $nama))
                    ->map(fn($kata) => strtoupper(substr($kata, 0, 1)))
                    ->join('');

                return '
                <div class="grid grid-cols-[40px_auto] gap-1 items-center">
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold ring-2 ring-blue-500 border-2 border-white">
                        ' . $inisial . '
                    </div>
                    <div>
                        <div class="font-semibold">' . $nama . '</div>
                        <div class="text-xs text-gray-500">' . $email . '</div>
                    </div>
                </div>';
            })
            ->addColumn('action', function ($row) {
                $detailUrl = route('permintaanolahdata.show', $row->id);
                $baseBtnClass = 'inline-flex items-center justify-center rounded-full text-xs transition ml-1 w-9 h-9 shadow-sm';

                $btnDetail = '<a href="' . $detailUrl . '" class="' . $baseBtnClass . ' bg-blue-600 text-white hover:bg-blue-800" title="Detail">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                          </a>';

                $btnBatal = '';
                if ($row->status === 'antrian') {
                    $btnBatal = '<button class="btn-batal-penugasan ' . $baseBtnClass . ' bg-red-600 text-white hover:bg-red-800"
                                    data-id="' . $row->id . '" title="Batalkan">
                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                             </button>';
                }

                $btnApproval = '';
                if ($row->status === 'selesai' && optional($row->hasilOlahan)->verifikasi_hasil === null) {
                    $btnApproval = '<a href="' . route('verifikasi.form', $row->hasilOlahan->id) . '" 
                                    class="' . $baseBtnClass . ' bg-green-600 text-white hover:bg-green-800"
                                    title="Verifikasi">
                                    <span class="material-symbols-outlined text-[18px]">task_alt</span>
                                </a>';
                }

                return '<div class="flex items-center">' . $btnDetail . $btnBatal . $btnApproval . '</div>';
            })
            ->rawColumns(['status', 'action', 'judul_permintaan', 'kode_transaksi', 'kategori', 'pengolah'])
            ->make(true);
    }

    /**
     * Menampilkan halaman detail dari satu permintaan olah data.
     *
     * Method ini mengambil data permintaan berdasarkan ID, lengkap dengan relasi `pemilikData` dan `kategori`.
     * Digunakan untuk menampilkan informasi rinci dalam view detail.
     *
     * @param  int  $id  ID permintaan olah data yang ingin ditampilkan.
     * @return \Illuminate\View\View  View `permintaanolahdata.detail` dengan data permintaan.
     */
    public function show($id)
    {
        $data = PermintaanData::with('pemilikData', 'kategori')->findOrFail($id);
        return view('permintaanolahdata.detail', compact('data'));
    }
}
