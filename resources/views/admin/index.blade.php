@extends('layouts.admin')

@section('content')
    <div class="max-w-full grid grid-cols-1 md:grid-cols-2 gap-8 m-5 bg-white rounded-lg p-6 border border-gray-300">
        <!-- Chart: Jumlah User -->
        <div class="border border-gray-300 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2 ">Jumlah User per Role</h2>
            <div style="height: 250px">
                <canvas id="userRoleChart"></canvas>
            </div>
        </div>
        <!-- Chart: Permintaan per Status -->
        <div class="border border-gray-300 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Permintaan Data per Status</h2>
            <div style="height: 250px">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Chart: Permintaan per Bulan -->
        <div class="col-span-1 md:col-span-2 border border-gray-300 p-4 rounded-lg bg-white shadow">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-lg font-semibold text-gray-700">Permintaan Data per Bulan</h2>

                <!-- 🔽 Dropdown filter tahun -->
                <form method="GET" id="filterTahunForm" class="flex items-center gap-2">
                    <label for="tahun" class="text-sm text-gray-600">Tahun:</label>
                    <select name="tahun" id="tahun" onchange="document.getElementById('filterTahunForm').submit()"
                        class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500">
                        @foreach ($data['tahun_tersedia'] as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $data['tahun_dipilih'] ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- 🧭 Chart bulanan -->
            <div style="height: 280px;">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>



    </div>
@endsection

@push('scripts')
    {{-- Skrip Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart 1: User per Role
        new Chart(document.getElementById('userRoleChart'), {
            type: 'bar',
            data: {
                labels: ['Petugas PST', 'Pengolah Data'],
                datasets: [{
                    label: 'Jumlah User',
                    data: [{{ $data['petugas_pst'] }}, {{ $data['pengolah_data'] }}],
                    backgroundColor: ['#3b82f6', '#9EC6F3']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        // Chart 2: Permintaan per Status
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Antrian', 'Proses', 'Selesai'],
                datasets: [{
                    data: [
                        {{ $data['status']['antrian'] }},
                        {{ $data['status']['proses'] }},
                        {{ $data['status']['selesai'] }}
                    ],
                    backgroundColor: ['#93c5fd', '#60a5fa', '#3b82f6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        // Chart 3: Permintaan per Bulan
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($data['bulan']) !!},
                datasets: [{
                    label: 'Permintaan Data',
                    data: {!! json_encode($data['permintaan_bulanan']) !!},
                    fill: false,
                    borderColor: '#6366f1',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
@endpush
