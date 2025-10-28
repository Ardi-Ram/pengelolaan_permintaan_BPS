<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Banner;
use App\Models\Subject;
use App\Models\LinkGroup;
use Illuminate\Support\Str;
use App\Models\CategoryData;
use App\Models\TabelDinamis;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\FooterLinkGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class TabelDinamisController extends Controller
{

    // Petugas PST

    /**
     * Menampilkan form untuk membuat tabel dinamis baru.
     * 
     * Data kategori diambil beserta subject-nya agar user bisa langsung memilih.
     */
    public function create()
    {
        $kategori = CategoryData::with('subjects')->get();
        return view('tabel_dinamis.create', compact('kategori'));
    }

    /**
     * Menyimpan data tabel dinamis ke database.
     * 
     * - Melakukan validasi input array `data`.
     * - Mengecek kesesuaian antara kategori dan subject.
     * - Menyimpan tiap entri sebagai record baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'data.*.judul' => 'required|string|max:255',
            'data.*.deskripsi' => 'required|string',
            'data.*.kategori_id' => 'required|exists:category_data,id',
            'data.*.subject_id' => 'required|exists:subjects,id',
            'data.*.deadline' => 'nullable|date',
        ]);

        foreach ($request->data as $index => $item) {
            $subject = Subject::find($item['subject_id']);

            // Pastikan subject sesuai dengan kategori
            if (!$subject || $subject->category_data_id != $item['kategori_id']) {
                return redirect()->back()->withErrors([
                    "data.$index.subject_id" => 'Subject tidak sesuai dengan kategori yang dipilih.'
                ])->withInput();
            }

            // Simpan data baru ke tabel_dinamis
            TabelDinamis::create([
                'judul' => $item['judul'],
                'deskripsi' => $item['deskripsi'],
                'kategori_id' => $item['kategori_id'],
                'subject_id' => $item['subject_id'],
                'deadline' => $item['deadline'] ?? null,
                'petugas_pst_id' => Auth::id(),
            ]);
        }

        return redirect()
            ->route('tabel-dinamis.create')
            ->with('success', 'Pendaftaran tabel statistik berhasil disimpan.');
    }

    /**
     * Menampilkan halaman daftar penugasan tabel dinamis.
     * 
     * Data kategori dan daftar pengguna berperan "pengolah_data"
     * dikirim ke view agar bisa digunakan untuk filter atau penugasan.
     */
    public function penugasanIndex()
    {
        $kategoriList = DB::table('category_data')->get();
        $pengolahList = User::role('pengolah_data')->get();

        return view('tabel_dinamis.penugasan', compact('pengolahList', 'kategoriList'));
    }


    /**
     * Mengambil data tabel dinamis untuk halaman penugasan.
     * 
     * - Menampilkan data berstatus "draf" dan "ditolak".
     * - Dipakai oleh DataTables di sisi frontend.
     */
    public function getPenugasanData(Request $request)
    {
        $data = TabelDinamis::with('kategori')
            ->where(function ($query) {
                // Data draf: belum ditugaskan dan belum ada alasan penolakan
                $query->whereNull('pengolah_id')
                    ->whereNull('alasan_penolakan');
            })
            ->orWhere(function ($query) {
                // Data ditolak: sudah ada pengolah tapi punya alasan penolakan
                $query->whereNotNull('pengolah_id')
                    ->whereNotNull('alasan_penolakan');
            })
            ->latest();

        // Format untuk DataTables
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('judul', function ($row) {
                // Kolom judul dengan ikon dan efek hover
                return '
                    <div class="flex items-center gap-x-2 max-w-[250px] text-sm" title="' . e($row->judul) . '">
                        <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                        <span class="truncate hover:whitespace-normal hover:bg-white hover:shadow-lg transition-all duration-200 rounded px-1" style="max-width: 200px;">
                            ' . e($row->judul) . '
                        </span>
                    </div>';
            })
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')
            ->addColumn('status', function ($row) {
                // Gaya umum untuk semua status
                $baseClass = "inline-flex items-center justify-between rounded-lg text-xs font-medium px-3 py-1 border w-28";

                $statusText = 'Tidak Dikenal';
                $dotColor = 'bg-gray-400';
                $borderColor = 'border-gray-300';
                $textColor = 'text-gray-700';

                if (is_null($row->pengolah_id) && is_null($row->alasan_penolakan)) {
                    $statusText = 'Draf';
                    $dotColor = 'bg-gray-500';
                } elseif (!is_null($row->pengolah_id) && !is_null($row->alasan_penolakan)) {
                    $statusText = 'Ditolak';
                    $dotColor = 'bg-red-500';
                    $borderColor = 'border-red-300';
                    $textColor = 'text-red-700';
                }

                // Tombol khusus untuk lihat alasan penolakan
                if ($statusText === 'Ditolak') {
                    $pengolahName = $row->pengolah?->name ?? 'Tidak Diketahui';
                    return '
                        <button 
                            class="btn-alasan ' . $baseClass . ' bg-white ' . $textColor . ' ' . $borderColor . ' hover:bg-gray-50"
                            data-alasan="' . e($row->alasan_penolakan) . '"
                            data-pengolah="' . e($pengolahName) . '"
                            title="Lihat alasan penolakan">
                            <span>' . $statusText . '</span>
                            <span class="w-2 h-2 rounded-full ' . $dotColor . '"></span>
                        </button>';
                }

                // Default tampilan status
                return '<span class="' . $baseClass . ' bg-white ' . $textColor . ' ' . $borderColor . '">
                    <span>' . $statusText . '</span>
                    <span class="w-2 h-2 rounded-full ' . $dotColor . '"></span>
                </span>';
            })
            ->addColumn('aksi', function ($row) {
                // Kelas tombol umum (bulat, kecil)
                $btnClass = "w-9 h-9 flex items-center justify-center transition rounded-full";

                return '
                    <div class="flex gap-2">
                        <!-- Tugaskan -->
                        <button 
                            class="assign-btn ' . $btnClass . ' bg-blue-500 text-white hover:bg-blue-600"
                            data-id="' . $row->id . '" 
                            data-judul="' . e($row->judul) . '"
                            title="Tugaskan">
                            <span class="material-symbols-outlined text-[18px]">person_add</span>
                        </button>

                        <!-- Edit -->
                        <a href="' . route('tabel-dinamis.edit', $row->id) . '"
                            class="' . $btnClass . ' bg-yellow-500 text-white hover:bg-yellow-600"
                            title="Edit">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </a>

                        <!-- Detail -->
                        <a href="' . route('tabel-dinamis.show', $row->id) . '" 
                            class="' . $btnClass . ' bg-green-600 text-white hover:bg-green-700"
                            title="Lihat Detail">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </a>
                    </div>';
            })
            ->rawColumns(['judul', 'status', 'aksi'])
            ->make(true);
    }

    /**
     * Menampilkan detail tabel dinamis berdasarkan ID.
     * 
     * Relasi kategori, subject, pengolah, dan petugasPst ikut dimuat.
     */
    public function show($id)
    {
        $tabel = TabelDinamis::with(['kategori', 'subject', 'pengolah', 'petugasPst'])->findOrFail($id);
        return view('tabel_dinamis.detail', compact('tabel'));
    }

    /**
     * Menampilkan form edit tabel dinamis.
     * 
     * Mengambil data kategori dan subject sesuai kategori yang dipilih.
     */
    public function edit($id)
    {
        $tabel = TabelDinamis::findOrFail($id);
        $kategori = CategoryData::with('subjects')->get();
        $subjectList = Subject::where('category_data_id', $tabel->kategori_id)->get();

        return view('tabel_dinamis.edit', compact('tabel', 'kategori', 'subjectList'));
    }
    /**
     * Update data tabel dinamis berdasarkan ID.
     * Validasi memastikan subject sesuai dengan kategori yang dipilih.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:category_data,id',
            'subject_id' => 'required|exists:subjects,id',
            'deadline' => 'nullable|date',
        ]);

        // Pastikan subject cocok dengan kategori
        $subject = Subject::find($request->subject_id);
        if (!$subject || $subject->category_data_id != $request->kategori_id) {
            return back()->withErrors([
                'subject_id' => 'Subject tidak sesuai dengan kategori yang dipilih.'
            ])->withInput();
        }

        // Update data tabel
        $tabel = TabelDinamis::findOrFail($id);
        $tabel->update($request->only('judul', 'deskripsi', 'kategori_id', 'subject_id', 'deadline'));

        return redirect()->route('tabel-dinamis.penugasan', $id)
            ->with('success', 'Data berhasil diperbarui.');
    }

    /**
     * Ambil daftar subject berdasarkan kategori (AJAX).
     * Dipakai untuk dropdown dinamis di form.
     */
    public function getSubjectsByKategori(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:category_data,id',
        ]);

        $subjects = Subject::where('category_data_id', $request->kategori_id)->get();
        return response()->json($subjects);
    }

    /**
     * Tugaskan pengolah data ke tabel dinamis tertentu.
     * Reset alasan penolakan jika sebelumnya pernah ditolak.
     */
    public function assignPengolah(Request $request, $id)
    {
        $request->validate([
            'pengolah_id' => 'required|exists:users,id',
        ]);

        $tabel = TabelDinamis::findOrFail($id);

        $tabel->update([
            'pengolah_id' => $request->pengolah_id,
            'alasan_penolakan' => null, // reset jika sebelumnya ditolak
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengolah berhasil ditugaskan.',
        ]);
    }

    /**
     * Tampilkan halaman status tabel dinamis.
     * Menyediakan daftar kategori untuk filter.
     */
    public function halamanStatus()
    {
        $kategoriList = CategoryData::all();
        return view('tabel_dinamis.status', compact('kategoriList'));
    }

    /**
     * Ambil data status tabel dinamis untuk DataTables (AJAX).
     * Hanya menampilkan data yang sudah ditugaskan dan belum ditolak.
     */
    public function getStatusData(Request $request)
    {
        $data = TabelDinamis::with(['kategori', 'pengolah'])
            ->whereNotNull('pengolah_id')    // Sudah ditugaskan
            ->whereNull('alasan_penolakan')  // Belum ditolak
            ->when($request->kategori_id, fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->when($request->search_judul, fn($q) => $q->where('judul', 'like', "%{$request->search_judul}%"))
            ->latest();

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                $searchValue = $request->input('search.value');
                if (!empty($searchValue)) {
                    $query->where('judul', 'like', "%{$searchValue}%");
                }
            })
            ->addIndexColumn()

            // Kolom judul dengan ikon dan efek hover
            ->addColumn('judul', fn($row) => '
                <div class="flex items-center gap-x-2 max-w-[250px] text-sm" title="' . e($row->judul) . '">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                    <span class="truncate hover:whitespace-normal hover:bg-white hover:shadow-lg rounded px-1 transition-all">' . e($row->judul) . '</span>
                </div>
            ')

            // Kolom kategori
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')

            // Kolom pengolah (dengan avatar huruf)
            ->addColumn('pengolah', function ($row) {
                if (!$row->pengolah) {
                    return '<i class="text-gray-400 italic">Belum ditugaskan</i>';
                }
                $nama = $row->pengolah->name;
                $email = $row->pengolah->email;
                $inisial = collect(explode(' ', $nama))
                    ->map(fn($kata) => strtoupper(substr($kata, 0, 1)))
                    ->join('');
                $inisial = substr($inisial, 0, 3);

                return '
                    <div class="grid grid-cols-[40px_auto] gap-3 items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">'
                    . $inisial . '</div>
                        <div>
                            <div class="font-semibold">' . $nama . '</div>
                            <div class="text-sm text-gray-500">' . $email . '</div>
                        </div>
                    </div>
                ';
            })

            // Kolom status (warna berdasarkan tahap)
            ->editColumn('status', function ($row) {
                $status = strtolower($row->status);
                $color = match ($status) {
                    'antrian' => 'bg-red-500',
                    'proses' => 'bg-yellow-500',
                    'menunggu publish' => 'bg-blue-500',
                    'published' => 'bg-green-500',
                    default => 'bg-gray-400',
                };

                return '
                    <span class="inline-flex items-center justify-between px-3 py-1 rounded-full text-sm font-medium bg-white border border-gray-300">
                        <span>' . ucfirst($status) . '</span>
                        <span class="w-2.5 h-2.5 rounded-full ' . $color . ' ml-2"></span>
                    </span>
                ';
            })

            // Kolom aksi (tombol dinamis sesuai status)
            ->addColumn('aksi', function ($row) {
                $btn = [];
                $base = 'w-9 h-9 rounded-full flex items-center justify-center transition';

                switch ($row->status) {
                    case 'antrian':
                        $btn[] = '<button class="btn-batalkan ' . $base . ' bg-red-500 text-white hover:bg-red-600"
                            data-id="' . $row->id . '" title="Batalkan Penugasan">
                            <span class="material-symbols-outlined text-[18px]">cancel</span>
                        </button>';
                        break;

                    case 'proses':
                        $btn[] = '<span class="' . $base . ' bg-yellow-100 text-yellow-700" title="Dalam Proses">
                            <span class="material-symbols-outlined text-[18px]">hourglass_top</span>
                        </span>';
                        break;

                    case 'menunggu publish':
                        if (is_null($row->verifikasi_pst)) {
                            $btn[] = '<a href="' . route('tabel-dinamis.verifikasi.form', $row->id) . '"
                                class="' . $base . ' bg-yellow-500 text-white hover:bg-yellow-600" title="Verifikasi">
                                <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            </a>';
                        } elseif ($row->verifikasi_pst == 1) {
                            $btn[] = '<a href="' . $row->link_hasil . '" target="_blank"
                                class="' . $base . ' bg-green-500 text-white hover:bg-green-600" title="Lihat Hasil">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>';
                            $btn[] = '<button class="btn-publish ' . $base . ' bg-blue-500 text-white hover:bg-blue-600"
                                data-id="' . $row->id . '" title="Publish">
                                <span class="material-symbols-outlined text-[18px]">upload</span>
                            </button>';
                        }
                        break;

                    case 'published':
                        $btn[] = '<a href="' . $row->link_hasil . '" target="_blank"
                            class="' . $base . ' bg-green-500 text-white hover:bg-green-600" title="Lihat Hasil">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </a>';
                        $btn[] = '<a href="' . $row->link_publish . '" target="_blank"
                            class="' . $base . ' bg-indigo-500 text-white hover:bg-indigo-600" title="Lihat Publikasi">
                            <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        </a>';
                        $btn[] = '<button type="button" class="btn-edit-publish ' . $base . ' bg-yellow-500 text-white hover:bg-yellow-600"
                            data-id="' . $row->id . '" title="Edit Link Publish">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>';
                        break;
                }

                return '<div class="flex gap-1">' . implode('', $btn) . '</div>';
            })
            ->rawColumns(['judul', 'pengolah', 'status', 'aksi'])
            ->make(true);
    }

    /**
     * Form verifikasi data hasil olahan sebelum publikasi.
     */
    public function formVerifikasi($id)
    {
        $tabel = TabelDinamis::findOrFail($id);
        return view('tabel_dinamis.verifikasi', compact('tabel'));
    }

    /**
     * Simpan hasil verifikasi oleh petugas PST.
     * Jika verifikasi ditolak (0), maka wajib isi catatan.
     */
    public function simpanVerifikasi(Request $request, $id)
    {
        $rules = [
            'verifikasi_pst' => 'required|in:1,0',
        ];

        // Jika ditolak → catatan wajib diisi
        if ($request->verifikasi_pst === '0') {
            $rules['catatan_verifikasi'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        $tabel = TabelDinamis::findOrFail($id);
        $tabel->verifikasi_pst = $validated['verifikasi_pst'];
        $tabel->verified_at = now();
        $tabel->catatan_verifikasi = $request->catatan_verifikasi;

        // Jika ditolak, kembalikan status ke "proses"
        if ($validated['verifikasi_pst'] == '0') {
            $tabel->status = 'proses';
        }

        $tabel->save();

        return redirect()
            ->route('tabel-dinamis.status')
            ->with('success', 'Hasil telah diverifikasi.');
    }

    /**
     * Publish data hasil olahan ke portal publik.
     * Mengubah status menjadi "published".
     */
    public function publish(Request $request, $id)
    {
        $request->validate([
            'link_publish' => 'required|url',
        ]);

        $tabel = TabelDinamis::findOrFail($id);
        $tabel->update([
            'link_publish' => $request->link_publish,
            'status' => 'published',
        ]);

        return response()->json([
            'message' => 'Link portal berhasil dipublish.',
        ]);
    }

    /**
     * Update link publikasi yang sudah dipublish sebelumnya.
     */
    public function updateLinkPublish(Request $request, $id)
    {
        $request->validate([
            'link_publish' => 'required|url',
        ]);

        $tabel = TabelDinamis::findOrFail($id);
        $tabel->update([
            'link_publish' => $request->link_publish,
        ]);

        return response()->json(['success' => true]);
    }



    // Role Pengolah

    /**
     * Tampilkan halaman daftar tabel dinamis.
     * Menyediakan data kategori untuk dropdown filter.
     */
    public function tabelDinamisIndex()
    {
        $kategoriList = CategoryData::all();
        return view('tabel_dinamis.daftarTabel', compact('kategoriList'));
    }

    /**
     * Ambil data tabel dinamis berdasarkan kategori dan status "antrian".
     * Data dikirim dalam format DataTables (JSON) untuk ditampilkan di frontend.
     */
    public function getTabelDinamis(Request $request)
    {
        $data = TabelDinamis::with(['kategori', 'petugasPst'])
            ->where('pengolah_id', Auth::id())
            ->where('status', 'antrian')
            ->whereNull('alasan_penolakan')
            ->when($request->kategori_id, fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->latest();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('judul', function ($row) {
                return '
                <div class="flex items-center gap-x-2 max-w-[250px] text-sm" title="' . e($row->judul) . '">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                    <span class="truncate hover:whitespace-normal hover:bg-white hover:shadow-lg transition-all duration-200 rounded px-1" style="max-width: 200px;">
                        ' . e($row->judul) . '
                    </span>
                </div>';
            })
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')
            ->addColumn('petugas_pst', function ($row) {
                return '
                    <div class="grid grid-cols-[40px_auto] items-center gap-2">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white font-bold text-sm">'
                    . strtoupper(implode('', array_slice(array_map(fn($w) => mb_substr($w, 0, 1), preg_split('/\s+/', trim($row->petugasPst?->name ?? ''))), 0, 3))) .
                    '</div>
                        <div class="flex flex-col leading-tight">
                            <span class="font-medium text-gray-800 truncate max-w-[120px]" title="' . e($row->petugasPst?->name ?? '-') . '">' . e($row->petugasPst?->name ?? '-') . '</span>
                            <span class="text-xs text-gray-500 truncate max-w-[120px]" title="' . e($row->petugasPst?->email ?? '-') . '">' . e($row->petugasPst?->email ?? '-') . '</span>
                        </div>
                    </div>';
            })
            ->addColumn('aksi', function ($row) {
                $btnBase = 'w-9 h-9 flex items-center justify-center rounded-full shadow-sm transition text-white';
                return '
                    <div class="flex items-center gap-2">
                        <button class="apply-btn ' . $btnBase . ' bg-blue-600 hover:bg-blue-800" data-id="' . $row->id . '" title="Terapkan">
                            <span class="material-icons-outlined text-base leading-none">check</span>
                        </button>
                        <button type="button" onclick="openTolakModal(' . $row->id . ')" class="' . $btnBase . ' bg-red-600 hover:bg-red-800" title="Tolak">
                            <span class="material-icons-outlined text-base leading-none">close</span>
                        </button>
                    </div>';
            })
            ->addColumn('status', function ($row) {
                $base = 'inline-flex items-center justify-between px-4 py-1 rounded-full text-sm font-medium border w-28 bg-white';
                return match ($row->status) {
                    'antrian' => '<span class="' . $base . ' border-yellow-300 text-yellow-700">Antrian<span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span></span>',
                    'proses' => '<span class="' . $base . ' border-blue-300 text-blue-700">Proses<span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span></span>',
                    'selesai' => '<span class="' . $base . ' border-green-300 text-green-700">Selesai<span class="w-2.5 h-2.5 rounded-full bg-green-500"></span></span>',
                    'ditolak' => '<span class="' . $base . ' border-red-300 text-red-700">Ditolak<span class="w-2.5 h-2.5 rounded-full bg-red-500"></span></span>',
                    default => '<span class="' . $base . ' border-gray-300 text-gray-500 italic">Tidak Dikenal<span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span></span>',
                };
            })
            ->filterColumn('judul', fn($q, $keyword) => $q->where('judul', 'like', "%{$keyword}%"))
            ->rawColumns(['aksi', 'judul', 'petugas_pst', 'status'])
            ->make(true);
    }

    /**
     * Mengubah status tabel dari "antrian" menjadi "proses" saat pengolah apply.
     * Hanya bisa dilakukan oleh pengolah yang ditugaskan pada tabel tersebut.
     */
    public function applyTabel(Request $request, $id)
    {
        $userId = Auth::id();

        $tabel = TabelDinamis::where('id', $id)
            ->where('status', 'antrian')
            ->first();

        if (!$tabel) {
            return response()->json(['success' => false, 'message' => 'Tabel tidak ditemukan atau bukan status antrian.'], 404);
        }

        if ($tabel->pengolah_id !== $userId) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak apply tabel ini.'], 403);
        }

        $tabel->update(['status' => 'proses']);

        return response()->json(['success' => true]);
    }

    /**
     * Tolak tugas tabel dinamis oleh pengolah data.
     * Wajib menyertakan alasan penolakan minimal 5 karakter.
     */
    public function tolakTabel(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5',
        ]);

        // Cari tabel berdasarkan ID dan pastikan milik pengolah yang login
        $tabel = TabelDinamis::where('id', $id)
            ->where('pengolah_id', Auth::id())
            ->firstOrFail();

        // Simpan alasan penolakan
        $tabel->update([
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('tabeldinamis')->with('success', 'Tugas berhasil ditolak.');
    }

    /**
     * Tampilkan halaman upload link hasil tabel dinamis.
     * Menyediakan daftar kategori untuk filter di form upload.
     */
    public function uploadPage()
    {
        $kategoriList = CategoryData::all();
        return view('tabel_dinamis.uploadlink', compact('kategoriList'));
    }

    /**
     * Ambil data tabel dinamis untuk halaman upload hasil.
     * Hanya menampilkan data milik pengolah yang login dan belum/ tidak valid verifikasinya.
     */
    public function getUploadData(Request $request)
    {
        $query = TabelDinamis::with(['kategori', 'petugasPst'])
            ->where('pengolah_id', auth::id())
            ->where(function ($q) {
                $q->whereNull('verifikasi_pst')     // Belum diverifikasi
                    ->orWhere('verifikasi_pst', false); // Tidak valid
            });

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()

            // Kolom Judul
            ->addColumn('judul', function ($row) {
                return '
                <div class="flex items-center gap-x-2 max-w-[250px] text-sm" title="' . e($row->judul) . '">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                    <span class="truncate hover:whitespace-normal hover:bg-white hover:shadow-lg transition-all duration-200 rounded px-1" style="max-width: 200px;">
                        ' . e($row->judul) . '
                    </span>
                </div>';
            })

            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')

            // Kolom petugas PST dengan inisial dan info kontak
            ->addColumn('petugas_pst', fn($row) => '
        <div class="grid grid-cols-[40px_auto] items-center gap-2">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-500 text-white font-bold text-sm">
                ' . strtoupper(implode('', array_slice(array_map(fn($w) => mb_substr($w, 0, 1), preg_split('/\s+/', trim($row->petugasPst?->name ?? ''))), 0, 3))) . '
            </div>
            <div class="flex flex-col leading-tight">
                <span class="font-medium text-gray-800 truncate max-w-[120px]" title="' . e($row->petugasPst?->name ?? '-') . '">' . e($row->petugasPst?->name ?? '-') . '</span>
                <span class="text-xs text-gray-500 truncate max-w-[120px]" title="' . e($row->petugasPst?->email ?? '-') . '">' . e($row->petugasPst?->email ?? '-') . '</span>
            </div>
        </div>
      ')

            ->addColumn('deadline', fn($row) => \Carbon\Carbon::parse($row->deadline)->format('d M Y'))

            // Kolom status verifikasi / progress
            ->addColumn('status_tampilan', function ($row) {
                $badgeBase = 'inline-flex items-center justify-between px-3 py-1 rounded-full text-sm font-medium bg-white border border-gray-300 min-w-[9rem]';

                if (is_null($row->verifikasi_pst)) {
                    if ($row->status === 'menunggu publish') {
                        return '<span class="' . $badgeBase . '"><span>Menunggu Verifikasi</span><span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span></span>';
                    }
                    return '<span class="' . $badgeBase . '"><span>Proses</span><span class="w-2.5 h-2.5 rounded-full bg-gray-500"></span></span>';
                }

                if (!$row->verifikasi_pst) {
                    return '<button type="button" class="' . $badgeBase . ' btn-catatan" onclick="showCatatan(`' . addslashes(e($row->catatan_verifikasi)) . '`)">
                                <span>Tidak Valid</span>
                                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
                            </button>';
                }

                return '-';
            })

            // Kolom tombol upload link
            ->addColumn('aksi', fn($row) => '
                <button class="upload-btn bg-blue-600 text-white px-3 py-1 rounded text-xs"
                    data-id="' . $row->id . '" data-judul="' . e($row->judul) . '">
                    <span class="material-symbols-outlined text-base">upload</span>
                </button>
            ')

            ->rawColumns(['status_tampilan', 'aksi', 'judul', 'petugas_pst'])
            ->make(true);
    }

    /**
     * Simpan atau perbarui link hasil pengolahan tabel.
     * Status otomatis berubah menjadi "menunggu publish" dan reset verifikasi sebelumnya.
     */
    public function uploadLink(Request $request, $id)
    {
        $request->validate([
            'link_hasil' => 'required|url'
        ]);

        // Pastikan data milik pengolah yang login dan sedang berstatus 'proses'
        $tabel = TabelDinamis::where('id', $id)
            ->where('pengolah_id', auth::id())
            ->where('status', 'proses')
            ->firstOrFail();

        // Update data tabel dengan link hasil
        $tabel->update([
            'link_hasil' => $request->link_hasil,
            'status' => 'menunggu publish',
            'verifikasi_pst' => null,
            'verified_at' => null,
            'catatan_verifikasi' => null
        ]);

        return response()->json(['success' => true]);
    }



    //kunjungan

    /**
     * Menampilkan halaman portal publik berisi daftar tabel statistik.
     * 
     * Mengambil data dari model:
     * - LinkGroup (beserta relasi `links`)
     * - Banner (hanya yang aktif)
     * - FooterLinkGroup (beserta relasi `links`)
     * - CategoryData (untuk filter kategori tabel)
     * 
     * @return \Illuminate\View\View  Mengembalikan view `kunjungan.tabelStatistik`
     */
    public function portalIndex()
    {
        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        $footerGroups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();
        $kategoriList = CategoryData::all();

        return view('kunjungan.tabelStatistik', compact('kategoriList', 'groups', 'footerGroups', 'banners'));
    }


    /**
     * Mengambil data tabel dinamis yang sudah dipublish untuk ditampilkan di portal publik.
     * 
     * Digunakan oleh DataTables frontend dengan opsi filter kategori.
     *
     * @param  \Illuminate\Http\Request  $request  Berisi parameter filter kategori (opsional)
     * @return \Yajra\DataTables\DataTableAbstract  Data tabel berisi judul, deskripsi, kategori, dan tanggal publikasi
     */
    public function getPortalData(Request $request)
    {
        $query = TabelDinamis::with('kategori')
            ->where('status', 'published');

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')
            ->addColumn(
                'judul',
                fn($row) => '
            <a href="' . $row->link_publish . '" 
               target="_blank" 
               class="text-blue-600 hover:underline font-medium">
               ' . e($row->judul) . '
            </a>'
            )
            ->addColumn('deskripsi', fn($row) => Str::limit($row->deskripsi, 100))
            ->addColumn('tanggal', fn($row) => $row->created_at->format('d M Y'))
            ->rawColumns(['judul'])
            ->make(true);
    }
}
