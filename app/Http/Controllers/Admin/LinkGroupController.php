<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LinkGroup;
use Illuminate\Http\Request;

class LinkGroupController extends Controller
{
    public function index()
    {
        $groups = LinkGroup::orderBy('order')->with('links')->get();
        return view('admin.link-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('admin.link-groups.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'order' => 'nullable|integer']);
        LinkGroup::create($request->only('name', 'order'));
        return redirect()->route('admin.link-groups.index')->with('success', 'Link Group berhasil dibuat');
    }

    public function edit(LinkGroup $linkGroup)
    {
        return view('admin.link-groups.edit', compact('linkGroup'));
    }

    public function update(Request $request, LinkGroup $linkGroup)
    {
        $request->validate(['name' => 'required|string|max:255', 'order' => 'nullable|integer']);
        $linkGroup->update($request->only('name', 'order'));
        return redirect()->route('admin.link-groups.index')->with('success', 'Link Group berhasil diperbarui');
    }

    public function destroy(LinkGroup $linkGroup)
    {
        $linkGroup->delete();
        return redirect()->route('admin.link-groups.index')->with('success', 'Link Group berhasil dihapus');
    }
}
