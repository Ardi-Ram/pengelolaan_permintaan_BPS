<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PermintaanData;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\ActivityLog;
use App\Models\HasilOlahan;


class AdminController extends \Illuminate\Routing\Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }
    public function index()
    {
        $data = [
            'petugas_pst' => User::role('petugas_pst')->count(),
            'pengolah_data' => User::role('pengolah_data')->count(),
            'status' => [
                'antrian' => PermintaanData::where('status', 'antrian')->count(),
                'proses' => PermintaanData::where('status', 'proses')->count(),
                'selesai' => PermintaanData::where('status', 'selesai')->count(),
            ],
            'bulan' => collect(range(1, 12))->map(function ($m) {
                return Carbon::create()->month($m)->translatedFormat('F');
            }),
            'permintaan_bulanan' => collect(range(1, 12))->map(function ($m) {
                return PermintaanData::whereMonth('created_at', $m)->count();
            }),

        ];

        return view('admin.index', compact('data'));
    }

    public function logActivity()
    {
        $logs = ActivityLog::with('user')->latest()->get();

        $logsByDate = $logs->groupBy(function ($log) {
            return $log->created_at->format('Y-m-d');
        });

        $today = Carbon::today();

        $rekapPetugas = User::role('petugas_pst')->withCount([
            'permintaanData as jumlah_permintaan' => function ($query) use ($today) {
                $query->whereDate('created_at', $today);
            }
        ])->get();

        $rekapPengolah = User::role('pengolah_data')->get()->map(function ($user) use ($today) {
            $jumlahHasilOlahan = HasilOlahan::whereHas('permintaanData', function ($query) use ($user, $today) {
                $query->where('pengolah_id', $user->id)
                    ->whereDate('created_at', $today);
            })->count();

            return (object)[
                'name' => $user->name,
                'jumlah_hasil_olahan' => $jumlahHasilOlahan,
            ];
        });

        return view('admin.logActivity', compact('logsByDate', 'rekapPetugas', 'rekapPengolah'));
    }
    public function userManagement()
    {
        $users = User::with('roles')->whereHas('roles', function ($query) {
            $query->whereIn('name', ['petugas_pst', 'pengolah_data']);
        })->get();

        return view('admin.user', compact('users'));
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.editUser', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,petugas_pst,pengolah_data',
            'password' => 'nullable|confirmed|min:8',
        ]);

        $user = User::findOrFail($id);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Update role user
        $user->syncRoles([$request->role]);

        return redirect()->route('show.admin.user')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('show.admin.user')->with('success', 'User berhasil dihapus!');
    }
    public function create()
    {
        return view('admin.tambahUser');
    }

    // Simpan User ke Database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,petugas_pst,pengolah_data',
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign role ke user baru pakai Spatie
        $user->assignRole($request->role);

        return redirect()->route('show.admin.user')->with('success', 'User berhasil ditambahkan!');
    }
}
