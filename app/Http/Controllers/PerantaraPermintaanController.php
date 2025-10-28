<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\PerantaraPermintaan;

class PerantaraPermintaanController extends Controller
{
    /**
     * Tampilkan daftar seluruh perantara permintaan (halaman admin).
     */
    public function index()
    {
        $perantara = PerantaraPermintaan::all();
        return view('admin.perantara', compact('perantara'));
    }

    /**
     * Tambah data perantara baru.
     */
    public function store(Request $request)
    {
        // Validasi agar nama unik dan wajib diisi
        $request->validate([
            'nama_perantara' => 'required|string|max:255|unique:perantara_data,nama_perantara'
        ]);

        // Simpan data baru
        PerantaraPermintaan::create($request->only('nama_perantara'));

        return redirect()->back()->with('success', 'Perantara berhasil ditambahkan');
    }

    /**
     * Perbarui data perantara berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        // Validasi nama unik kecuali untuk dirinya sendiri
        $request->validate([
            'nama_perantara' => 'required|string|max:255|unique:perantara_data,nama_perantara,' . $id
        ]);

        $perantara = PerantaraPermintaan::findOrFail($id);
        $perantara->update($request->only('nama_perantara'));

        return redirect()->back()->with('success', 'Perantara berhasil diperbarui');
    }

    /**
     * Hapus data perantara berdasarkan ID.
     */
    public function destroy($id)
    {
        $perantara = PerantaraPermintaan::findOrFail($id);
        $perantara->delete();

        return redirect()->back()->with('success', 'Perantara berhasil dihapus');
    }
}
