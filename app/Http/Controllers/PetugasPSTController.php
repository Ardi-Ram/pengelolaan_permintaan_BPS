<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PemilikData;
use Illuminate\Http\Request;
use App\Models\PermintaanData;
use App\Models\PermintaanOlahData;
use Illuminate\Support\Facades\Auth;

class PetugasPSTController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware('role:petugas_pst');
    }


    public function index()
    {
        $userId = Auth::id();

        // Hanya hitung data milik petugas login
        $antrianCount = PermintaanData::where('status', 'antrian')
            ->where('petugas_pst_id', $userId)
            ->count();

        $prosesCount = PermintaanData::where('status', 'proses')
            ->where('petugas_pst_id', $userId)
            ->count();

        $selesaiCount = PermintaanData::where('status', 'selesai')
            ->where('petugas_pst_id', $userId)
            ->count();

        $latestRequests = PermintaanData::where('petugas_pst_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $averageTime = PermintaanData::where('status', 'selesai')
            ->where('petugas_pst_id', $userId)
            ->selectRaw("AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_hours")
            ->value('avg_hours');

        $trendData = PermintaanData::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('petugas_pst_id', $userId)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $permintaanPerPengolah = PermintaanData::where('status', 'proses')
            ->whereNotNull('pengolah_id')
            ->where('petugas_pst_id', $userId)
            ->with('pengolah')
            ->selectRaw('pengolah_id, COUNT(*) as total')
            ->groupBy('pengolah_id')
            ->get();

        // Kalau daftar kode transaksi juga perlu disesuaikan (optional)
        $daftarKodeTransaksi = PemilikData::whereHas('permintaanData', function ($q) use ($userId) {
            $q->where('petugas_pst_id', $userId);
        })->paginate(5);

        $pengolahList = User::role('pengolah_data')->withCount([
            'permintaanPengolah as jumlah_antrian' => function ($query) use ($userId) {
                $query->where('status', 'antrian')->where('petugas_pst_id', $userId);
            },
            'permintaanPengolah as jumlah_proses' => function ($query) use ($userId) {
                $query->where('status', 'proses')->where('petugas_pst_id', $userId);
            },
        ])->get();

        return view('petugas_pst.dashboard', compact(
            'antrianCount',
            'prosesCount',
            'selesaiCount',
            'latestRequests',
            'averageTime',
            'trendData',
            'permintaanPerPengolah',
            'daftarKodeTransaksi',
            'pengolahList'
        ));
    }
}
