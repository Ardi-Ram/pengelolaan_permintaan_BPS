<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryData;

class CategoryDataController extends Controller
{
    /**
     * Tampilkan semua kategori beserta subject-nya.
     */
    public function index()
    {
        $categories = CategoryData::with('subjects')->get(); // Ambil kategori beserta subject
        return view('admin.kategori', compact('categories'));
    }

    /**
     * Tampilkan form tambah kategori.
     */
    public function create()
    {
        return view('category_data.create');
    }

    /**
     * Simpan kategori baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:category_data,nama_kategori'
        ]);

        CategoryData::create($request->only('nama_kategori'));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit($id)
    {
        $category = CategoryData::findOrFail($id);
        return view('category_data.edit', compact('category'));
    }

    /**
     * Perbarui data kategori.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:category_data,nama_kategori,' . $id
        ]);

        $category = CategoryData::findOrFail($id);
        $category->update($request->only('nama_kategori'));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Hapus kategori.
     */
    public function destroy($id)
    {
        $category = CategoryData::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }

    /**
     * Tambahkan subject baru pada kategori tertentu.
     */
    public function storeSubject(Request $request, $categoryId)
    {
        $request->validate([
            'nama_subject' => 'required|string|max:255'
        ]);

        $category = CategoryData::findOrFail($categoryId);
        $category->subjects()->create([
            'nama_subject' => $request->nama_subject
        ]);

        return redirect()->route('categories.index')->with('success', 'Subject berhasil ditambahkan');
    }

    /**
     * Hapus subject berdasarkan ID.
     */
    public function destroySubject($id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('categories.index')->with('success', 'Subject berhasil dihapus');
    }

    /**
     * Perbarui subject berdasarkan ID.
     */
    public function updateSubject(Request $request, $id)
    {
        $request->validate([
            'nama_subject' => 'required|string|max:255'
        ]);

        $subject = \App\Models\Subject::findOrFail($id);
        $subject->update(['nama_subject' => $request->nama_subject]);

        return redirect()->route('categories.index')->with('success', 'Subject berhasil diperbarui');
    }
}
