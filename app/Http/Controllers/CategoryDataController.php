<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategoryData;

class CategoryDataController extends Controller
{
    public function index()
    {
        $categories = CategoryData::with('subjects')->get(); // with subjects
        return view('admin.kategori', compact('categories'));
    }


    public function create()
    {
        return view('category_data.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:category_data,nama_kategori'
        ]);

        CategoryData::create($request->only('nama_kategori'));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $category = CategoryData::findOrFail($id);
        return view('category_data.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:category_data,nama_kategori,' . $id
        ]);

        $category = CategoryData::findOrFail($id);
        $category->update($request->only('nama_kategori'));

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy($id)
    {
        $category = CategoryData::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus');
    }

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

    public function destroySubject($id)
    {
        $subject = \App\Models\Subject::findOrFail($id);
        $subject->delete();

        return redirect()->route('categories.index')->with('success', 'Subject berhasil dihapus');
    }
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
