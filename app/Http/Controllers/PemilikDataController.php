<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\LinkGroup;
use App\Models\PemilikData;
use Illuminate\Http\Request;
use App\Models\FooterLinkGroup;

class PemilikDataController extends Controller
{
    /**
     * Tampilkan halaman utama pengunjung.
     * Menampilkan grup tautan, footer, dan banner aktif.
     */
    public function index()
    {
        // Ambil grup link beserta link-nya yang sudah diurutkan
        $groups = LinkGroup::with(['links' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();

        // Ambil grup link untuk footer
        $footerGroups = FooterLinkGroup::with(['links' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();

        // Ambil banner aktif
        $banners = Banner::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Tampilkan ke view pengunjung
        return view('kunjungan.guest', compact('groups', 'footerGroups', 'banners'));
    }


    /**
     * Cari status permintaan data berdasarkan kode transaksi.
     */
    public function cari(Request $request)
    {
        // Validasi input kode transaksi
        $request->validate([
            'kode_transaksi' => 'required|string',
        ]);

        // Cek pemilik data berdasarkan kode transaksi
        $pemilik = PemilikData::where('kode_transaksi', $request->kode_transaksi)->first();

        if (!$pemilik) {
            return back()->with('error', 'Kode transaksi tidak ditemukan.');
        }

        // Ambil semua permintaan data terkait beserta relasinya
        $permintaan = $pemilik->permintaanData()
            ->with(['kategori', 'pengolah', 'hasilOlahan'])
            ->get();

        // Data tambahan untuk layout
        $groups = LinkGroup::with(['links' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();

        $footerGroups = FooterLinkGroup::with(['links' => fn($q) => $q->orderBy('order')])
            ->orderBy('order')
            ->get();

        $banners = Banner::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Tampilkan halaman hasil pencarian
        return view('kunjungan.guest', compact('pemilik', 'permintaan', 'groups', 'footerGroups', 'banners'));
    }
}
