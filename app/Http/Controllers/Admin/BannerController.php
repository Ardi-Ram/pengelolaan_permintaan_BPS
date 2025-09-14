<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\LinkGroup;
use App\Models\Banner;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function index()
    {
        $allLinks = Link::with('linkGroup')->orderBy('order')->get();
        $groups = LinkGroup::with('links')->orderBy('order')->get();
        $banners = Banner::orderBy('order')->get();

        return view('admin.links.index', compact('allLinks', 'groups', 'banners'));
    }

    // ----------------- Link CRUD -----------------
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'link_group_id' => 'nullable|exists:link_groups,id',
            'order' => 'nullable|integer',
        ]);

        Link::create($request->only('label', 'url', 'link_group_id', 'order'));
        return redirect()->route('admin.links.index')->with('success', 'Link berhasil ditambahkan.');
    }

    public function update(Request $request, Link $link)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'order' => 'nullable|integer',
            'link_group_id' => 'nullable|exists:link_groups,id',
        ]);

        $link->update($request->only('label', 'url', 'order', 'link_group_id'));
        return redirect()->route('admin.links.index')->with('success', 'Link berhasil diperbarui.');
    }

    public function destroy(Link $link)
    {
        $link->delete();
        return redirect()->route('admin.links.index')->with('success', 'Link berhasil dihapus.');
    }

    // ----------------- LinkGroup CRUD -----------------
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        LinkGroup::create($request->only('name', 'order'));
        return redirect()->route('admin.links.index')->with('success', 'Group berhasil ditambahkan.');
    }

    public function updateGroup(Request $request, LinkGroup $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $group->update($request->only('name', 'order'));
        return redirect()->route('admin.links.index')->with('success', 'Group berhasil diperbarui.');
    }

    public function destroyGroup(LinkGroup $group)
    {
        $group->delete();
        return redirect()->route('admin.links.index')->with('success', 'Group berhasil dihapus.');
    }

    // ----------------- Banner CRUD -----------------
    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'required|image|max:2048', // max 2MB
        ]);

        $path = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'order' => $request->order,
            'image_path' => $path,
            'is_active' => true,
        ]);

        return redirect()->route('admin.links.index')->with('success', 'Banner berhasil ditambahkan.');
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->only('title', 'order', 'is_active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $data['image_path'] = $path;
        }

        $banner->update($data);
        return redirect()->route('admin.links.index')->with('success', 'Banner berhasil diperbarui.');
    }

    public function destroyBanner(Banner $banner)
    {
        $banner->delete();
        return redirect()->route('admin.links.index')->with('success', 'Banner berhasil dihapus.');
    }
}
