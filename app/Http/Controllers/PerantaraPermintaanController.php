<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\PerantaraPermintaan;

class PerantaraPermintaanController extends Controller
{
    public function index()
    {
        $perantara = PerantaraPermintaan::all();
        return view('admin.perantara', compact('perantara'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_perantara' => 'required|string|max:255|unique:perantara_data,nama_perantara'
        ]);

        PerantaraPermintaan::create($request->only('nama_perantara'));

        return redirect()->back()->with('success', 'Perantara berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_perantara' => 'required|string|max:255|unique:perantara_data,nama_perantara,' . $id
        ]);

        $perantara = PerantaraPermintaan::findOrFail($id);
        $perantara->update($request->only('nama_perantara'));

        return redirect()->back()->with('success', 'Perantara berhasil diperbarui');
    }

    public function destroy($id)
    {
        $perantara = PerantaraPermintaan::findOrFail($id);
        $perantara->delete();

        return redirect()->back()->with('success', 'Perantara berhasil dihapus');
    }
}
