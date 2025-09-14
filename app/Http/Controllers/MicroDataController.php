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
    public function index()
    {
        $data = MicroData::with('kategori')->latest()->paginate(10);
        return view('data-mikro.index', compact('data'));
    }
    public function show($id)
    {
        $microData = MicroData::with('items')->findOrFail($id);
        return view('data-mikro.show', compact('microData'));
    }


    public function items($id)
    {
        $microData = MicroData::with('items')->findOrFail($id);
        return view('data-mikro.dataset.index', compact('microData'));
    }

    public function create()
    {
        $kategori = CategoryData::all();
        return view('data-mikro.create', compact('kategori'));
    }

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

    public function edit(MicroData $micro_data)
    {
        $kategori = CategoryData::all();
        return view('data-mikro.edit', compact('micro_data', 'kategori'));
    }

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

    public function destroy(MicroData $micro_data)
    {
        if ($micro_data->gambar) {
            Storage::disk('public')->delete($micro_data->gambar);
        }
        $micro_data->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }

    //dataset
    public function createItem($id)
    {
        $microData = MicroData::findOrFail($id);
        return view('data-mikro.dataset.create', compact('microData'));
    }

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
    public function editItem($id, $itemId)
    {
        $microData = MicroData::findOrFail($id);
        $item = MicroDataItem::where('micro_data_id', $id)->findOrFail($itemId);
        return view('data-mikro.dataset.edit', compact('microData', 'item'));
    }

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
    public function destroyItem($id, $itemId)
    {
        $item = MicroDataItem::where('micro_data_id', $id)->findOrFail($itemId);
        $item->delete();

        return redirect()->route('data-mikro.dataset.index', $id)->with('success', 'Dataset berhasil dihapus.');
    }


    // Halaman daftar data mikro untuk pengunjung
    public function publicIndex(Request $request)
    {
        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $footerGroups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $banners = Banner::where('is_active', true)->orderBy('order')->get();

        $kategoriId = $request->query('kategori');

        $query = MicroData::with('kategori')->latest();

        if ($kategoriId) {
            $query->where('kategori_id', $kategoriId);
        }

        $dataMikro = $query->paginate(9);

        $kategoriList = \App\Models\CategoryData::all();

        return view('kunjungan.data-mikro', compact('dataMikro', 'groups', 'footerGroups', 'banners', 'kategoriList', 'kategoriId'));
    }

    // Halaman detail dataset dari salah satu data mikro
    public function publicShow($id)
    {
        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $footerGroups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $banners = Banner::where('is_active', true)->orderBy('order')->get();
        $dataMikro = MicroData::with('items')->findOrFail($id);
        return view('kunjungan.data-mikro-detail', compact('dataMikro', 'groups', 'footerGroups', 'banners'));
    }
}
