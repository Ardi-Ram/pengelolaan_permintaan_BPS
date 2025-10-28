<?php

namespace App\Http\Controllers;

use App\Models\MicroData;
use App\Models\MicroDataItem;
use App\Models\CategoryData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\banner;
use App\Models\LinkGroup;
use App\Models\PemilikData;
use App\Models\FooterLinkGroup;

class MicroDataController extends Controller
{
    /**
     * Tampilkan daftar data mikro.
     * Menampilkan data dengan relasi kategori dan paginasi.
     */
    public function index()
    {
        $data = MicroData::with('kategori')->latest()->paginate(10);
        return view('data-mikro.index', compact('data'));
    }

    /**
     * Tampilkan detail satu data mikro.
     */
    public function show($id)
    {
        $microData = MicroData::with('items')->findOrFail($id);
        return view('data-mikro.show', compact('microData'));
    }

    /**
     * Tampilkan daftar item (dataset) dari data mikro tertentu.
     */
    public function items($id)
    {
        $microData = MicroData::with('items')->findOrFail($id);
        return view('data-mikro.dataset.index', compact('microData'));
    }

    /**
     * Tampilkan form untuk menambahkan data mikro baru.
     */
    public function create()
    {
        $kategori = CategoryData::all();
        return view('data-mikro.create', compact('kategori'));
    }

    /**
     * Simpan data mikro baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori_id' => 'required|exists:Category_Data,id',
        ]);

        $path = null;
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('gambar_micro_data', 'public');
        }

        MicroData::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $path,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('data-mikro.index')->with('success', 'Data berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit untuk data mikro tertentu.
     */
    public function edit(MicroData $micro_data)
    {
        $kategori = CategoryData::all();
        return view('data-mikro.edit', compact('micro_data', 'kategori'));
    }

    /**
     * Perbarui data mikro yang sudah ada.
     */
    public function update(Request $request, MicroData $micro_data)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori_id' => 'required|exists:category_data,id',
        ]);

        if ($request->hasFile('gambar')) {
            if ($micro_data->gambar) {
                Storage::disk('public')->delete($micro_data->gambar);
            }
            $micro_data->gambar = $request->file('gambar')->store('gambar_micro_data', 'public');
        }

        $micro_data->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'gambar' => $micro_data->gambar,
            'kategori_id' => $request->kategori_id,
        ]);

        return redirect()->route('data-mikro.index')->with('success', 'Data berhasil diupdate.');
    }

    /**
     * Hapus data mikro beserta file gambarnya.
     */
    public function destroy(MicroData $micro_data)
    {
        if ($micro_data->gambar) {
            Storage::disk('public')->delete($micro_data->gambar);
        }
        $micro_data->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }


    //dataset
    /**
     * Tampilkan form untuk menambahkan item (dataset) baru ke data mikro.
     */
    public function createItem($id)
    {
        $microData = MicroData::findOrFail($id);
        return view('data-mikro.dataset.create', compact('microData'));
    }

    /**
     * Simpan item (dataset) baru yang terkait dengan data mikro tertentu.
     */
    public function storeItem(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'level_penyajian' => 'nullable|string|max:100',
            'harga' => 'nullable|numeric|min:0',
            'ukuran_file' => 'nullable|string|max:50',
            'link' => 'nullable|url|max:255',
        ]);

        MicroDataItem::create([
            'micro_data_id' => $id,
            'judul' => $request->judul,
            'level_penyajian' => $request->level_penyajian,
            'harga' => $request->harga,
            'ukuran_file' => $request->ukuran_file,
            'link' => $request->link,
        ]);

        return redirect()->route('data-mikro.show', $id)->with('success', 'Dataset berhasil ditambahkan.');
    }

    /**
     * Tampilkan form untuk mengedit item (dataset) tertentu dari data mikro.
     */
    public function editItem($id, $itemId)
    {
        $microData = MicroData::findOrFail($id);
        $item = MicroDataItem::where('micro_data_id', $id)->findOrFail($itemId);
        return view('data-mikro.dataset.edit', compact('microData', 'item'));
    }

    /**
     * Perbarui data item (dataset) tertentu yang terkait dengan data mikro.
     */
    public function updateItem(Request $request, $id, $itemId)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'level_penyajian' => 'nullable|string|max:100',
            'harga' => 'nullable|numeric|min:0',
            'ukuran_file' => 'nullable|string|max:50',
            'link' => 'nullable|url|max:255',
        ]);

        $item = MicroDataItem::where('micro_data_id', $id)->findOrFail($itemId);
        $item->update($request->only(['judul', 'level_penyajian', 'harga', 'ukuran_file', 'link']));

        return redirect()->route('data-mikro.dataset.index', $id)->with('success', 'Dataset berhasil diperbarui.');
    }

    /**
     * Hapus item (dataset) tertentu dari data mikro.
     */
    public function destroyItem($id, $itemId)
    {
        $item = MicroDataItem::where('micro_data_id', $id)->findOrFail($itemId);
        $item->delete();

        return redirect()->route('data-mikro.dataset.index', $id)->with('success', 'Dataset berhasil dihapus.');
    }



    /**
     * Tampilkan halaman daftar Data Mikro untuk pengunjung umum.
     */
    public function publicIndex(Request $request)
    {
        // Ambil menu navigasi utama
        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        // Ambil link bagian footer
        $footerGroups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        // Ambil banner aktif
        $banners = Banner::where('is_active', true)->orderBy('order')->get();

        // Filter kategori jika ada
        $kategoriId = $request->query('kategori');

        $query = MicroData::with('kategori')->latest();
        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }

        $dataMikro = $query->paginate(9);
        $kategoriList = \App\Models\CategoryData::all();

        return view('kunjungan.data-mikro', compact(
            'dataMikro',
            'groups',
            'footerGroups',
            'banners',
            'kategoriList',
            'kategoriId'
        ));
    }

    /**
     * Tampilkan detail satu Data Mikro beserta dataset-nya untuk pengunjung umum.
     */
    public function publicShow($id)
    {
        // Ambil data navigasi dan banner aktif
        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $footerGroups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $banners = Banner::where('is_active', true)->orderBy('order')->get();

        // Ambil data mikro beserta daftar item (dataset)
        $dataMikro = MicroData::with('items')->findOrFail($id);

        return view('kunjungan.data-mikro-detail', compact(
            'dataMikro',
            'groups',
            'footerGroups',
            'banners'
        ));
    }
}
