<?php

namespace App\Http\Controllers\Admin;

use App\Models\Link;
use App\Models\LinkGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Support\Facades\Storage;

class LinkController extends Controller
{
    public function index()
    {
        $allLinks = Link::with('group')->orderBy('order')->get();
        $groups = LinkGroup::orderBy('order')->get();
        $banners = Banner::orderBy('order')->get();
        return view('admin.links.index', compact('allLinks', 'groups', 'banners'));
    }

    // CRUD LINK
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'link_group_id' => 'nullable|exists:link_groups,id',
            // tidak perlu order di form tambah
        ]);

        // Selalu append ke akhir
        $maxOrder = Link::where('link_group_id', $request->link_group_id)->max('order') ?? 0;
        $order = $maxOrder + 1;

        Link::create([
            'label' => $request->label,
            'url' => $request->url,
            'link_group_id' => $request->link_group_id,
            'order' => $order
        ]);

        return redirect()->route('admin.links.index')->with('success', 'Link berhasil dibuat');
    }

    public function update(Request $request, Link $link)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'link_group_id' => 'nullable|exists:link_groups,id',
            'order' => 'nullable|integer'
        ]);

        // Kalau order kosong, pakai order lama
        $link->update([
            'label' => $request->label,
            'url' => $request->url,
            'link_group_id' => $request->link_group_id,
            'order' => $request->order ?: $link->order,
        ]);

        return redirect()->route('admin.links.index')->with('success', 'Link berhasil diperbarui');
    }


    public function destroy(Link $link)
    {
        $link->delete();
        return redirect()->route('admin.links.index')->with('success', 'Link berhasil dihapus');
    }

    // CRUD GROUP
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // tidak perlu order di form tambah
        ]);

        $maxOrder = LinkGroup::max('order') ?? 0;

        LinkGroup::create([
            'name' => $request->name,
            'order' => $maxOrder + 1,
        ]);

        return redirect()->route('admin.links.index')->with('success', 'Group berhasil ditambahkan');
    }

    public function updateGroup(Request $request, LinkGroup $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer'
        ]);

        $group->update([
            'name' => $request->name,
            'order' => $request->order ?: $group->order,
        ]);

        return redirect()->route('admin.links.index')->with('success', 'Group berhasil diperbarui');
    }


    public function destroyGroup(LinkGroup $group)
    {
        $group->delete();
        return redirect()->route('admin.links.index')->with('success', 'Group berhasil dihapus');
    }



    // CRUD BANNER
    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            // tidak perlu order di form tambah
            'image' => 'required|image|max:2048'
        ]);

        // Simpan file
        $path = $request->file('image')->store('banners', 'public');

        // Selalu append ke akhir
        $maxOrder = \App\Models\Banner::max('order') ?? 0;
        $order = $maxOrder + 1;

        \App\Models\Banner::create([
            'title' => $request->title,
            'order' => $order,
            'image_path' => $path,
            'is_active' => true
        ]);

        return redirect()->route('admin.links.index')->with('success', 'Banner berhasil ditambahkan');
    }

    public function updateBanner(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $data = [
            'title' => $request->title,
            'order' => $request->order ?: $banner->order,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image_path);
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.links.index')->with('success', 'Banner berhasil diperbarui');
    }


    public function destroyBanner(Banner $banner)
    {
        // hapus file fisik (opsional)
        Storage::disk('public')->delete($banner->image_path);
        $banner->delete();

        return redirect()->route('admin.links.index')->with('success', 'Banner berhasil dihapus');
    }

    // ... (fungsi-fungsi sebelumnya)

    // tambahkan use

    // === SWAP ORDER UNTUK GROUP ===
    public function moveGroupUp(LinkGroup $group)
    {
        $prev = LinkGroup::where('order', '<', $group->order)
            ->orderBy('order', 'desc')->first();
        if ($prev) {
            [$group->order, $prev->order] = [$prev->order, $group->order];
            $group->save();
            $prev->save();
        }
        return back();
    }

    public function moveGroupDown(LinkGroup $group)
    {
        $next = LinkGroup::where('order', '>', $group->order)
            ->orderBy('order')->first();
        if ($next) {
            [$group->order, $next->order] = [$next->order, $group->order];
            $group->save();
            $next->save();
        }
        return back();
    }

    // === SWAP ORDER UNTUK LINK ===
    public function moveLinkUp(Link $link)
    {
        $prev = Link::where('link_group_id', $link->link_group_id)
            ->where('order', '<', $link->order)
            ->orderBy('order', 'desc')->first();
        if ($prev) {
            [$link->order, $prev->order] = [$prev->order, $link->order];
            $link->save();
            $prev->save();
        }
        return back();
    }

    public function moveLinkDown(Link $link)
    {
        $next = Link::where('link_group_id', $link->link_group_id)
            ->where('order', '>', $link->order)
            ->orderBy('order')->first();
        if ($next) {
            [$link->order, $next->order] = [$next->order, $link->order];
            $link->save();
            $next->save();
        }
        return back();
    }

    // === SWAP ORDER UNTUK BANNER ===
    public function moveBannerUp(Banner $banner)
    {
        $prev = Banner::where('order', '<', $banner->order)
            ->orderBy('order', 'desc')->first();
        if ($prev) {
            [$banner->order, $prev->order] = [$prev->order, $banner->order];
            $banner->save();
            $prev->save();
        }
        return back();
    }

    public function moveBannerDown(Banner $banner)
    {
        $next = Banner::where('order', '>', $banner->order)
            ->orderBy('order')->first();
        if ($next) {
            [$banner->order, $next->order] = [$next->order, $banner->order];
            $banner->save();
            $next->save();
        }
        return back();
    }

    public function guestPage()
    {
        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $banners = Banner::where('is_active', true)->orderBy('order')->get();

        return view('kunjungan.guest', compact('groups', 'banners'));
    }
}
