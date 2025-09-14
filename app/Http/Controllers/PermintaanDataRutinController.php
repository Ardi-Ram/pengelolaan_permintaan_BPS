<?php

namespace App\Http\Controllers;

use App\Models\PermintaanDataRutin;
use Illuminate\Support\Facades\Auth;
use App\Models\CategoryData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PermintaanDataRutinController extends Controller
{
    public function index()
    {
        $dataRutin = PermintaanDataRutin::with('pengolah')->latest()->get();
        return view('admin.index', compact('dataRutin'));
    }
    public function status()
    {
        $dataRutin = PermintaanDataRutin::with('pengolah')->latest()->get();
        return view('admin.statusDataRutin', compact('dataRutin'));
    }


    public function form()
    {

        $categories = CategoryData::all();
        $pengolahs = User::role('pengolah_data')->get();
        return view('permintaanolahdata.dataRutin', compact('categories', 'pengolahs'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:category_data,id',
            'pengolah_id' => 'required|exists:users,id',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        PermintaanDataRutin::create([
            'kode_permintaan' => 'RUTIN-' . strtoupper(Str::random(6)),
            'judul' => $request->judul,
            'kategori_id' => $request->kategori_id,
            'pengolah_id' => $request->pengolah_id,
            'deskripsi' => $request->deskripsi,
            'admin_id' => Auth::user()->id,
            'tanggal_dibuat'  => now(),
            'status'          => 'antrian',
        ]);

        return redirect()->route('permintaan_data_rutin.form')
            ->with('success', 'Permintaan Data Rutin berhasil ditambahkan');
    }


    // Menampilkan form untuk mengedit permintaan data rutin
    public function edit($id)
    {
        $permintaan = PermintaanDataRutin::findOrFail($id);
        $categories = CategoryData::all();
        $pengolahs = User::role('Pengolah Data')->get();  // Gunakan role 'Pengolah Data'
        return view('permintaan_data_rutin.edit', compact('permintaan', 'categories', 'pengolahs'));
    }

    // Memperbarui permintaan data rutin
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_permintaan' => 'required|unique:permintaan_data_rutin,kode_permintaan,' . $id,
            'kategori_id' => 'required|exists:category_data,id',
            'pengolah_id' => 'required|exists:users,id',
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $permintaan = PermintaanDataRutin::findOrFail($id);
        $permintaan->update([
            'kode_permintaan' => $request->kode_permintaan,
            'kategori_id' => $request->kategori_id,
            'pengolah_id' => $request->pengolah_id,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('permintaan_data_rutin.index')->with('success', 'Permintaan Data Rutin berhasil diperbarui');
    }

    // Menghapus permintaan data rutin
    public function destroy($id)
    {
        $permintaan = PermintaanDataRutin::findOrFail($id);
        $permintaan->delete();

        return redirect()->route('permintaan_data_rutin.index')->with('success', 'Permintaan Data Rutin berhasil dihapus');
    }
}
