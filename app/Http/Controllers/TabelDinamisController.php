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

    public function create()
    {
        $kategori = CategoryData::with('subjects')->get(); // <--- Eager load
        return view('tabel_dinamis.create', compact('kategori'));
    }

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

            if (!$subject || $subject->category_data_id != $item['kategori_id']) {
                return redirect()->back()->withErrors([
                    "data.$index.subject_id" => 'Subject tidak sesuai dengan kategori yang dipilih.'
                ])->withInput();
            }

            TabelDinamis::create([
                'judul' => $item['judul'],
                'deskripsi' => $item['deskripsi'],
                'kategori_id' => $item['kategori_id'],
                'subject_id' => $item['subject_id'],
                'deadline' => $item['deadline'] ?? null,
                'petugas_pst_id' => Auth::id(),
            ]);
        }


        return redirect()->route('tabel-dinamis.create')->with('success', 'Pendaftaran tabel statistik berhasil disimpan.');
    }

    public function penugasanIndex()
    {
        $kategoriList = DB::table('category_data')->get();
        $pengolahList = User::role('pengolah_data')->get();
        return view('tabel_dinamis.penugasan', compact('pengolahList', 'kategoriList'));
    }

    public function getPenugasanData(Request $request)
    {
        $data = TabelDinamis::with('kategori')
            ->where(function ($query) {
                $query->whereNull('pengolah_id')
                    ->whereNull('alasan_penolakan'); // Draf
            })
            ->orWhere(function ($query) {
                $query->whereNotNull('pengolah_id')
                    ->whereNotNull('alasan_penolakan'); // Ditolak
            })
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
            ->addColumn('status', function ($row) {
                // Base style sama untuk semua status
                $baseClass = "inline-flex items-center justify-between rounded-lg text-xs font-medium px-3 py-1 border w-28"; // w-28 biar sama panjang

                // Default warna bulatan & teks
                $statusText = 'Tidak Dikenal';
                $dotColor = 'bg-gray-400';
                $borderColor = 'border-gray-300';
                $textColor = 'text-gray-700';

                if (is_null($row->pengolah_id) && is_null($row->alasan_penolakan)) {
                    // Draf
                    $statusText = 'Draf';
                    $dotColor = 'bg-gray-500';
                    $borderColor = 'border-gray-300';
                    $textColor = 'text-gray-700';
                } elseif (!is_null($row->pengolah_id) && !is_null($row->alasan_penolakan)) {
                    // Ditolak
                    $statusText = 'Ditolak';
                    $dotColor = 'bg-red-500';
                    $borderColor = 'border-red-300';
                    $textColor = 'text-red-700';
                }

                // Untuk status "Ditolak", tetap pakai button biar bisa klik lihat alasan
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

                // Default span
                return '<span class="' . $baseClass . ' bg-white ' . $textColor . ' ' . $borderColor . '">
                <span>' . $statusText . '</span>
                <span class="w-2 h-2 rounded-full ' . $dotColor . '"></span>
            </span>';
            })

            ->addColumn('aksi', function ($row) {
                // Kelas dasar tombol: ukuran sama, center icon (tanpa border)
                $btnClass = "w-9 h-9 flex items-center justify-center transition rounded-full";

                return '
<div class="flex gap-2">
    <!-- Tugaskan -->
    <button 
        class="assign-btn ' . $btnClass . ' bg-blue-500 text-white hover:bg-blue-600"
        data-id="' . $row->id . '" 
        data-judul="' . e($row->judul) . '"
        title="Tugaskan">
        <span class="material-symbols-outlined text-[18px] leading-none">person_add</span>
    </button>

    <!-- Edit -->
    <a href="' . route('tabel-dinamis.edit', $row->id) . '"
        class="' . $btnClass . ' bg-yellow-500 text-white hover:bg-yellow-600"
        title="Edit">
        <span class="material-symbols-outlined text-[18px] leading-none">edit</span>
    </a>

    <!-- Lihat Detail -->
    <a href="' . route('tabel-dinamis.show', $row->id) . '" 
        class="' . $btnClass . ' bg-green-600 text-white hover:bg-green-700"
        title="Lihat Detail">
        <span class="material-symbols-outlined text-[18px] leading-none">visibility</span>
    </a>
</div>';
            })


            ->rawColumns(['judul', 'status', 'aksi'])
            ->make(true);
    }

    public function show($id)
    {
        $tabel = TabelDinamis::with(['kategori', 'subject', 'pengolah', 'petugasPst'])->findOrFail($id);
        return view('tabel_dinamis.detail', compact('tabel'));
    }


    public function edit($id)
    {
        $tabel = TabelDinamis::findOrFail($id);
        $kategori = CategoryData::with('subjects')->get();
        $subjectList = Subject::where('category_data_id', $tabel->kategori_id)->get();

        return view('tabel_dinamis.edit', compact('tabel', 'kategori', 'subjectList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori_id' => 'required|exists:category_data,id',
            'subject_id' => 'required|exists:subjects,id',
            'deadline' => 'nullable|date',
        ]);

        $subject = Subject::find($request->subject_id);
        if (!$subject || $subject->category_data_id != $request->kategori_id) {
            return back()->withErrors([
                'subject_id' => 'Subject tidak sesuai dengan kategori yang dipilih.'
            ])->withInput();
        }

        $tabel = TabelDinamis::findOrFail($id);
        $tabel->update($request->only('judul', 'deskripsi', 'kategori_id', 'subject_id', 'deadline'));

        return redirect()->route('tabel-dinamis.penugasan', $id)->with('success', 'Data berhasil diperbarui.');
    }

    public function getSubjectsByKategori(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:category_data,id',
        ]);

        $subjects = Subject::where('category_data_id', $request->kategori_id)->get();
        return response()->json($subjects);
    }

    public function assignPengolah(Request $request, $id)
    {
        $request->validate([
            'pengolah_id' => 'required|exists:users,id',
        ]);

        $tabel = TabelDinamis::findOrFail($id);

        $tabel->update([
            'pengolah_id' => $request->pengolah_id,
            'alasan_penolakan' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengolah berhasil ditugaskan.'
        ]);
    }


    public function halamanStatus()
    {
        $kategoriList = CategoryData::all();
        return view('tabel_dinamis.status', compact('kategoriList'));
    }

    public function getStatusData(Request $request)
    {
        $data = TabelDinamis::with(['kategori', 'pengolah'])
            ->whereNotNull('pengolah_id') // ✅ Sudah ditugaskan
            ->whereNull('alasan_penolakan') // ✅ Belum pernah ditolak
            ->when($request->kategori_id, fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->when($request->search_judul, function ($query) use ($request) {
                $query->where('judul', 'like', '%' . $request->search_judul . '%');
            })

            ->orderBy('created_at', 'desc');

        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                $searchValue = $request->input('search.value'); // ✅ cara aman ambil search
                if (!empty($searchValue)) {
                    $query->where('judul', 'like', "%{$searchValue}%");
                }
            })
            ->addIndexColumn()

            // Kolom Judul
            ->addColumn('judul', function ($row) {
                return '
                <div class="flex items-center gap-x-2 max-w-[250px] text-sm" title="' . e($row->judul) . '">
                    <span class="material-symbols-outlined text-blue-500 text-[20px]">description</span>
                    <span class="truncate hover:whitespace-normal hover:bg-white hover:shadow-lg transition-all duration-200 rounded px-1" style="max-width: 200px;">
                        ' . e($row->judul) . '
                    </span>
                </div>
            ';
            })

            // Kolom Kategori
            ->addColumn('kategori', fn($row) => $row->kategori->nama_kategori ?? '-')

            // Kolom Pengolah
            ->addColumn('pengolah', function ($row) {
                if (!$row->pengolah) {
                    return '<i class="text-gray-400 italic">Belum ditugaskan</i>';
                }

                $nama  = $row->pengolah->name;
                $email = $row->pengolah->email;

                // Ambil inisial dari setiap kata, lalu batasi maksimal 3 huruf
                $inisial = collect(explode(' ', $nama))
                    ->map(fn($kata) => strtoupper(substr($kata, 0, 1)))
                    ->join('');
                $inisial = substr($inisial, 0, 3);

                return '
                <div class="grid grid-cols-[40px_auto] gap-3 items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">
                        ' . $inisial . '
                    </div>
                    <div>
                        <div class="font-semibold">' . $nama . '</div>
                        <div class="text-sm text-gray-500">' . $email . '</div>
                    </div>
                </div>
            ';
            })

            // Kolom Status
            ->editColumn('status', function ($row) {
                $colorClass = match ($row->status) {
                    'antrian'           => 'bg-red-500',
                    'proses'            => 'bg-yellow-500',
                    'menunggu publish'  => 'bg-blue-500',
                    'published'         => 'bg-green-500',
                    default             => 'bg-gray-400',
                };

                return '
        <span class="inline-flex items-center justify-between min-w-[7rem] px-2 py-1 text-sm font-medium border border-gray-300 rounded-full bg-white">
            <span>' . ucfirst($row->status) . '</span>
            <span class="w-2.5 h-2.5 rounded-full ' . $colorClass . '"></span>
        </span>
    ';
            })
            ->addColumn('aksi', function ($row) {
                $btns = [];

                // Class umum untuk tombol
                $btnClass = 'w-9 h-9 rounded-full flex items-center justify-center transition';

                // Status: Antrian
                if ($row->status === 'antrian') {
                    $btns[] = '
    <button class="btn-batalkan ' . $btnClass . ' bg-red-500 text-white hover:bg-red-600"
        data-id="' . $row->id . '" data-judul="' . e($row->judul) . '" title="Batalkan Penugasan">
        <span class="material-symbols-outlined text-[18px] leading-none">cancel</span>
    </button>
    ';
                }

                // Status: Proses
                elseif ($row->status === 'proses') {
                    $btns[] = '
    <span class="' . $btnClass . ' bg-yellow-100 text-yellow-700" title="Dalam Proses">
        <span class="material-symbols-outlined text-[18px] leading-none">hourglass_top</span>
    </span>
    ';
                }

                // Status: Menunggu Publish
                elseif ($row->status === 'menunggu publish') {
                    if (is_null($row->verifikasi_pst)) {
                        $btns[] = '
        <a href="' . route('tabel-dinamis.verifikasi.form', $row->id) . '" 
            class="' . $btnClass . ' bg-yellow-500 text-white hover:bg-yellow-600" title="Verifikasi">
            <span class="material-symbols-outlined text-[18px] leading-none">check_circle</span>
        </a>
        ';
                    } elseif ($row->verifikasi_pst == 1) {
                        $btns[] = '
        <a href="' . $row->link_hasil . '" target="_blank"
            class="' . $btnClass . ' bg-green-500 text-white hover:bg-green-600" title="Lihat Hasil">
            <span class="material-symbols-outlined text-[18px] leading-none">visibility</span>
        </a>
        ';
                        $btns[] = '
        <button class="btn-publish ' . $btnClass . ' bg-blue-500 text-white hover:bg-blue-600"
            data-id="' . $row->id . '" data-judul="' . e($row->judul) . '" title="Publish">
            <span class="material-symbols-outlined text-[18px] leading-none">upload</span>
        </button>
        ';
                    }
                }

                // Status: Published
                elseif ($row->status === 'published') {
                    $btns[] = '
    <a href="' . $row->link_hasil . '" target="_blank"
        class="' . $btnClass . ' bg-green-500 text-white hover:bg-green-600" title="Lihat Hasil">
        <span class="material-symbols-outlined text-[18px] leading-none">visibility</span>
    </a>
    ';
                    $btns[] = '
    <a href="' . $row->link_publish . '" target="_blank"
        class="' . $btnClass . ' bg-indigo-500 text-white hover:bg-indigo-600" title="Lihat Publikasi">
        <span class="material-symbols-outlined text-[18px] leading-none">open_in_new</span>
    </a>
    ';
                    $btns[] = '
    <button type="button" class="btn-edit-publish ' . $btnClass . ' bg-yellow-500 text-white hover:bg-yellow-600"
        data-id="' . $row->id . '" data-link="' . e($row->link_publish) . '" title="Edit Link Publish">
        <span class="material-symbols-outlined text-[18px] leading-none">edit</span>
    </button>
    ';
                }

                // Bungkus semua tombol dengan flex + gap agar rapi
                return '<div class="flex gap-1">' . implode('', $btns) . '</div>';
            })
            ->rawColumns(['judul', 'pengolah', 'status', 'aksi'])
            ->addIndexColumn()
            ->make(true);
    }


    public function batalkanPenugasan($id)
    {
        $tabel = TabelDinamis::findOrFail($id);

        if ($tabel->status !== 'antrian') {
            return response()->json(['success' => false, 'message' => 'Tabel bukan dalam status antrian.']);
        }

        $tabel->update([
            'pengolah_id' => null,
            'status' => 'antrian',
            'alasan_penolakan' => null,
            'verifikasi_pst' => null,
            'verified_at' => null,
            'link_hasil' => null,
            'catatan_verifikasi' => null,
        ]);

        return response()->json(['success' => true, 'message' => 'Penugasan berhasil dibatalkan.']);
    }

    public function formVerifikasi($id)
    {
        $tabel = TabelDinamis::findOrFail($id);
        return view('tabel_dinamis.verifikasi', compact('tabel'));
    }

    public function simpanVerifikasi(Request $request, $id)
    {
        $rules = [
            'verifikasi_pst' => 'required|in:1,0',
        ];

        if ($request->verifikasi_pst === '0') {
            $rules['catatan_verifikasi'] = 'required|string|max:1000';
        }

        $validated = $request->validate($rules);

        $tabel = TabelDinamis::findOrFail($id);
        $tabel->verifikasi_pst = $validated['verifikasi_pst'];
        $tabel->verified_at = now();
        $tabel->catatan_verifikasi = $request->catatan_verifikasi;


        if ($validated['verifikasi_pst'] == '0') {
            $tabel->status = 'proses';
        }

        $tabel->save();

        return redirect()->route('tabel-dinamis.status')->with('success', 'Hasil telah diverifikasi.');
    }



    public function publish(Request $request, $id)
    {
        $request->validate([
            'link_publish' => 'required|url'
        ]);

        $tabel = TabelDinamis::findOrFail($id);
        $tabel->link_publish = $request->link_publish;
        $tabel->status = 'published';
        $tabel->save();

        return response()->json([
            'message' => 'Link portal berhasil dipublish.'
        ]);
    }
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


    // PengolahController.php

    public function tabelDinamisIndex()
    {
        $kategoriList = CategoryData::all();
        return view('tabel_dinamis.daftarTabel', compact('kategoriList'));
    }

    public function getTabelDinamis(Request $request)
    {
        $data = TabelDinamis::with('kategori')
            ->where('pengolah_id', Auth::id())
            ->where('status', 'antrian')
            ->whereNull('alasan_penolakan') // ✅ Tambahan ini
            ->when($request->kategori_id, function ($query) use ($request) {
                $query->where('kategori_id', $request->kategori_id);
            })
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
            ->addColumn('aksi', function ($row) {
                return '
                <button class="apply-btn bg-blue-500 text-white px-3 py-1 rounded text-xs mr-2" data-id="' . $row->id . '">
                    Apply
                </button>
                <button type="button" onclick="openTolakModal(' . $row->id . ')" class="bg-red-600 text-white px-3 py-1 rounded text-xs">
                    Tolak
                </button>';
            })
            ->editColumn('status', fn($row) => ucfirst($row->status))
            ->rawColumns(['aksi', 'judul'])
            ->make(true);
    }



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


    public function tolakTabel(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|min:5',
        ]);

        $tabel = TabelDinamis::where('id', $id)
            ->where('pengolah_id', Auth::id())
            ->firstOrFail();

        $tabel->update([
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return redirect()->route('tabeldinamis')->with('success', 'Tugas berhasil ditolak.');
    }


    public function uploadPage()
    {
        $kategoriList = CategoryData::all();
        return view('tabel_dinamis.uploadlink', compact('kategoriList'));
    }
    public function getUploadData(Request $request)
    {
        $query = TabelDinamis::with(['kategori', 'petugasPst'])
            ->where('pengolah_id', auth::id())
            ->where(function ($q) {
                $q->whereNull('verifikasi_pst') // Belum diverifikasi
                    ->orWhere('verifikasi_pst', false); // Tidak valid
            });

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($query)
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
            ->addColumn('deadline', fn($row) => \Carbon\Carbon::parse($row->deadline)->format('d M Y'))

            // 🔥 Kolom Status Tampilan
            ->addColumn('status_tampilan', function ($row) {
                // Default style
                $badgeBase = 'inline-flex items-center justify-between px-3 py-1 rounded-full text-sm font-medium bg-white border border-gray-300 min-w-[9rem]';

                // Jika belum diverifikasi
                if (is_null($row->verifikasi_pst)) {
                    if ($row->status === 'menunggu publish') {
                        return '
                <span class="' . $badgeBase . '">
                    <span>Menunggu Verifikasi</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-500"></span>
                </span>
            ';
                    } else {
                        return '
                <span class="' . $badgeBase . '">
                    <span>Proses</span>
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-500"></span>
                </span>
            ';
                    }
                }

                // Jika tidak valid
                if (!$row->verifikasi_pst) {
                    return '
            <button type="button" 
                    class="' . $badgeBase . ' btn-catatan"
                    onclick="showCatatan(`' . addslashes(e($row->catatan_verifikasi)) . '`)">
                <span>Tidak Valid</span>
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span>
            </button>
        ';
                }

                // Tidak ditampilkan kalau difilter di query
                return '-';
            })


            ->addColumn('aksi', function ($row) {
                return '<button class="upload-btn bg-blue-600 text-white px-3 py-1 rounded text-xs"
                data-id="' . $row->id . '" data-judul="' . e($row->judul) . '">
                <span class="material-symbols-outlined text-base">
                    upload
                </span>
            </button>
            ';
            })

            ->rawColumns(['status_tampilan', 'aksi', 'judul', 'petugas_pst'])
            ->make(true);
    }


    public function uploadLink(Request $request, $id)
    {
        $request->validate([
            'link_hasil' => 'required|url'
        ]);

        $tabel = TabelDinamis::where('id', $id)
            ->where('pengolah_id', auth::id())
            ->where('status', 'proses')
            ->firstOrFail();

        $tabel->update([
            'link_hasil' => $request->link_hasil,
            'status' => 'menunggu publish',
            'verifikasi_pst' => null,          // reset verifikasi
            'verified_at' => null,             // reset tanggal verifikasi
            'catatan_verifikasi' => null       // hapus catatan lama
        ]);

        return response()->json(['success' => true]);
    }


    //kunjungan 
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
        return view('kunjungan.tabelStatistik', compact('kategoriList',  'groups', 'footerGroups', 'banners',));
    }

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
                fn($row) =>
                '<a href="' . $row->link_publish . '" 
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
