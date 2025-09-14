<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\LinkGroup;
use App\Models\PemilikData;
use Illuminate\Http\Request;
use App\Models\FooterLinkGroup;

class PemilikDataController extends Controller
{
    public function index()
    {
        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $footerGroups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $banners = Banner::where('is_active', true)->orderBy('order')->get();

        return view('kunjungan.guest', compact('groups', 'footerGroups', 'banners'));
    }


    public function cari(Request $request)
    {

        $request->validate([
            'kode_transaksi' => 'required|string',
        ]);

        $pemilik = PemilikData::where('kode_transaksi', $request->kode_transaksi)->first();

        if (!$pemilik) {
            return back()->with('error', 'Kode transaksi tidak ditemukan.');
        }
        $permintaan = $pemilik->permintaanData()
            ->with(['kategori', 'pengolah', 'hasilOlahan']) // <- pakai camelCase!
            ->get();

        $groups = LinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $footerGroups = FooterLinkGroup::with(['links' => function ($q) {
            $q->orderBy('order');
        }])->orderBy('order')->get();

        $banners = Banner::where('is_active', true)->orderBy('order')->get();

        return view('kunjungan.guest', compact('pemilik', 'permintaan', 'groups', 'footerGroups', 'banners'));
    }
}
