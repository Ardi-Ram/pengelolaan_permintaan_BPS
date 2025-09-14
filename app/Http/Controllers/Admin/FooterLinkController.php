<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FooterLink;
use App\Models\FooterLinkGroup;
use Illuminate\Http\Request;

class FooterLinkController extends Controller
{
    public function index()
    {
        $groups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        return view('admin.footer_links.index', compact('groups'));
    }

    // CRUD LINK
    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'footer_link_group_id' => 'required|exists:footer_link_groups,id',
        ]);

        // append ke akhir
        $maxOrder = FooterLink::where('footer_link_group_id', $request->footer_link_group_id)->max('order') ?? 0;

        FooterLink::create([
            'label' => $request->label,
            'url' => $request->url,
            'footer_link_group_id' => $request->footer_link_group_id,
            'order' => $maxOrder + 1
        ]);

        return back()->with('success', 'Link footer berhasil ditambahkan');
    }

    public function update(Request $request, FooterLink $link)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'url' => 'required|url',
            'footer_link_group_id' => 'required|exists:footer_link_groups,id',
            'order' => 'nullable|integer',
        ]);

        $link->update([
            'label' => $request->label,
            'url' => $request->url,
            'footer_link_group_id' => $request->footer_link_group_id,
            'order' => $request->order ?: $link->order,
        ]);

        return back()->with('success', 'Link footer berhasil diperbarui');
    }

    public function destroy(FooterLink $link)
    {
        $link->delete();
        return back()->with('success', 'Link footer berhasil dihapus');
    }

    // CRUD GROUP
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $maxOrder = FooterLinkGroup::max('order') ?? 0;

        FooterLinkGroup::create([
            'name' => $request->name,
            'order' => $maxOrder + 1
        ]);

        return back()->with('success', 'Group footer berhasil ditambahkan');
    }

    public function updateGroup(Request $request, FooterLinkGroup $group)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $group->update([
            'name' => $request->name,
            'order' => $request->order ?: $group->order,
        ]);

        return back()->with('success', 'Group footer berhasil diperbarui');
    }

    public function destroyGroup(FooterLinkGroup $group)
    {
        $group->delete();
        return back()->with('success', 'Group footer berhasil dihapus');
    }

    // === SWAP ORDER UNTUK GROUP ===
    public function moveGroupUp(FooterLinkGroup $group)
    {
        $prev = FooterLinkGroup::where('order', '<', $group->order)
            ->orderBy('order', 'desc')->first();
        if ($prev) {
            [$group->order, $prev->order] = [$prev->order, $group->order];
            $group->save();
            $prev->save();
        }
        return back();
    }

    public function moveGroupDown(FooterLinkGroup $group)
    {
        $next = FooterLinkGroup::where('order', '>', $group->order)
            ->orderBy('order')->first();
        if ($next) {
            [$group->order, $next->order] = [$next->order, $group->order];
            $group->save();
            $next->save();
        }
        return back();
    }

    // === SWAP ORDER UNTUK LINK ===
    public function moveLinkUp(FooterLink $link)
    {
        $prev = FooterLink::where('footer_link_group_id', $link->footer_link_group_id)
            ->where('order', '<', $link->order)
            ->orderBy('order', 'desc')->first();
        if ($prev) {
            [$link->order, $prev->order] = [$prev->order, $link->order];
            $link->save();
            $prev->save();
        }
        return back();
    }

    public function moveLinkDown(FooterLink $link)
    {
        $next = FooterLink::where('footer_link_group_id', $link->footer_link_group_id)
            ->where('order', '>', $link->order)
            ->orderBy('order')->first();
        if ($next) {
            [$link->order, $next->order] = [$next->order, $link->order];
            $link->save();
            $next->save();
        }
        return back();
    }
}
