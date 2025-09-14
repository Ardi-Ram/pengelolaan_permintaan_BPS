@extends('layouts.petugas')

@section('content')
    <div class="p-6 space-y-6 bg-white m-5 rounded-lg border border-gray-300">
        <h1 class="text-2xl font-semibold text-gray-800">Dashboard Petugas PST</h1>
        <!-- Ringkasan Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kartu Antrian -->
            <div
                class="bg-yellow-50 hover:shadow-lg transition-all duration-300 border border-yellow-200 p-5 rounded-xl flex items-center gap-5">
                <span class="material-symbols-outlined text-yellow-500 text-5xl bg-yellow-100 p-3 rounded-full shadow-inner">
                    hourglass_empty
                </span>
                <div>
                    <p class="text-gray-700 text-sm font-medium">Permintaan Antrian</p>
                    <h2 class="text-2xl font-bold text-yellow-700">{{ $antrianCount }}</h2>
                </div>
            </div>

            <!-- Kartu Proses -->
            <div
                class="bg-blue-50 hover:shadow-lg transition-all duration-300 border border-blue-200 p-5 rounded-xl flex items-center gap-5">
                <span class="material-symbols-outlined text-blue-500 text-5xl bg-blue-100 p-3 rounded-full shadow-inner">
                    sync
                </span>
                <div>
                    <p class="text-gray-700 text-sm font-medium">Sedang Diproses</p>
                    <h2 class="text-2xl font-bold text-blue-700">{{ $prosesCount }}</h2>
                </div>
            </div>

            <!-- Kartu Selesai -->
            <div
                class="bg-green-50 hover:shadow-lg transition-all duration-300 border border-green-200 p-5 rounded-xl flex items-center gap-5">
                <span class="material-symbols-outlined text-green-500 text-5xl bg-green-100 p-3 rounded-full shadow-inner">
                    check_circle
                </span>
                <div>
                    <p class="text-gray-700 text-sm font-medium">Selesai</p>
                    <h2 class="text-2xl font-bold text-green-700">{{ $selesaiCount }}</h2>
                </div>
            </div>
        </div>



        <div class="mt-10">
            <h2 class="text-xl font-semibold mb-4 text-gray-700">📄 Kode Transaksi Terbaru per Pemilik</h2>

            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-6 py-3">Nama Pemilik</th>
                            <th class="px-6 py-3">Email</th>
                            <th class="px-6 py-3">Kode Transaksi</th>
                            <th class="px-6 py-3">Tanggal Dibuat</th>
                            <th class="px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($daftarKodeTransaksi as $pemilik)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $pemilik->nama_pemilik }}</td>
                                <td class="px-6 py-4">{{ $pemilik->email }}</td>
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $pemilik->kode_transaksi }}</td>
                                <td class="px-6 py-4">{{ $pemilik->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('permintaanolahdata.status', ['search' => $pemilik->kode_transaksi]) }}"
                                        class="btn px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                        Cari Status
                                    </a>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center px-6 py-4 text-gray-500">Tidak ada data tersedia</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $daftarKodeTransaksi->links() }}
            </div>
        </div>




        <div class="bg-white shadow rounded-md p-6 mt-6">
            <h2 class="text-xl font-bold mb-4 text-gray-800">📊 Monitoring Pengolah Data</h2>

            <table class="table-auto w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Nama Pengolah</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-center">🕓 Antrian</th>
                        <th class="px-4 py-2 text-center">⚙️ Diproses</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengolahList as $pengolah)
                        <tr class="border-b">
                            <td class="px-4 py-2">{{ $pengolah->name }}</td>
                            <td class="px-4 py-2">{{ $pengolah->email }}</td>
                            <td class="px-4 py-2 text-center text-blue-600 font-semibold">
                                {{ $pengolah->jumlah_antrian }}
                            </td>
                            <td class="px-4 py-2 text-center text-green-600 font-semibold">
                                {{ $pengolah->jumlah_proses }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>



        <div class="bg-white shadow-md border border-gray-200 p-4 rounded-lg flex items-center gap-4">
            <span class="material-symbols-outlined text-indigo-500 text-4xl">schedule</span>
            <div>
                <p class="text-gray-600 text-sm">Rata-rata Waktu Penyelesaian</p>
                <h2 class="text-xl font-semibold text-gray-800">
                    {{ number_format($averageTime, 1) }} jam
                </h2>
            </div>
        </div>




        <!-- Chart.js CDN -->

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const ctx = document.getElementById('statusChart').getContext('2d');
                const statusChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Antrian', 'Proses', 'Selesai'],
                        datasets: [{
                            label: 'Jumlah',
                            data: [{{ $antrianCount }}, {{ $prosesCount }}, {{ $selesaiCount }}],
                            backgroundColor: ['#fde68a', '#93c5fd', '#86efac'],
                            borderColor: ['#facc15', '#3b82f6', '#22c55e'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            </script>
            <script>
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                const trendChart = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendData->pluck('date')) !!},
                        datasets: [{
                            label: 'Jumlah Permintaan',
                            data: {!! json_encode($trendData->pluck('total')) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: '#bfdbfe',
                            fill: true,
                            tension: 0.4,
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            </script>
        @endpush
    @endsection
