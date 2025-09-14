<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HasilOlahan;

class HasilOlahanController extends Controller
{
    public function index()
    {
        $hasil = HasilOlahan::all();
        return view('hasil_olahan.index', compact('hasil'));
    }

    public function create()
    {
        return view('hasil_olahan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_file' => 'required|string|max:255',
            'path' => 'required|string'
        ]);

        HasilOlahan::create($request->all());
        return redirect()->route('hasil-olahan.index')->with('success', 'Hasil olahan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $hasil = HasilOlahan::findOrFail($id);
        return view('hasil_olahan.edit', compact('hasil'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_file' => 'required|string|max:255',
            'path' => 'required|string'
        ]);

        $hasil = HasilOlahan::findOrFail($id);
        $hasil->update($request->all());
        return redirect()->route('hasil-olahan.index')->with('success', 'Hasil olahan berhasil diperbarui');
    }

    public function destroy($id)
    {
        $hasil = HasilOlahan::findOrFail($id);
        $hasil->delete();
        return redirect()->route('hasil-olahan.index')->with('success', 'Hasil olahan berhasil dihapus');
    }
}
