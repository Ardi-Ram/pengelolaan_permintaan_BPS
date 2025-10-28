@extends('layouts.app')
@section('title', 'Dashboard Pengolah')

@section('content')
    <h1 class="text-2xl font-bold flex items-center gap-2 p-5 bg-white border-b border-gray-200 shadow-sm">
        <span class="material-symbols-outlined text-blue-600 text-3xl">dashboard</span>
        Dashboard Pengolah Data
    </h1>

    <!-- Stat Cards -->
    <div
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 p-5 bg-white border border-gray-200 shadow-sm m-5 rounded-lg">
        @php
            $stats = [
                [
                    'label' => 'Ditugaskan',
                    'icon' => 'hourglass_empty',
                    'bg' => 'bg-yellow-100',
                    'text' => 'text-yellow-800',
                    'iconColor' => 'text-yellow-600',
                    'value' => $antrian,
                ],
                [
                    'label' => 'Diproses',
                    'icon' => 'sync',
                    'bg' => 'bg-blue-100',
                    'text' => 'text-blue-800',
                    'iconColor' => 'text-blue-600',
                    'value' => $proses,
                ],
                [
                    'label' => 'Selesai',
                    'icon' => 'check_circle',
                    'bg' => 'bg-green-100',
                    'text' => 'text-green-800',
                    'iconColor' => 'text-green-600',
                    'value' => $selesai,
                ],
                [
                    'label' => 'Total',
                    'icon' => 'summarize',
                    'bg' => 'bg-gray-100',
                    'text' => 'text-gray-800',
                    'iconColor' => 'text-gray-600',
                    'value' => $total,
                ],
            ];
        @endphp

        @foreach ($stats as $stat)
            <div
                class="{{ $stat['bg'] }} relative border p-4 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300">
                {{-- Icon besar semi transparan sebagai watermark --}}
                <span
                    class="material-symbols-outlined absolute text-[120px] opacity-10 right-2 bottom-0 {{ $stat['iconColor'] }}">
                    {{ $stat['icon'] }}
                </span>

                {{-- Konten utama --}}
                <div class="flex items-center gap-4 relative z-10">
                    <span class="material-symbols-outlined {{ $stat['iconColor'] }} text-3xl">
                        {{ $stat['icon'] }}
                    </span>
                    <div>
                        <p class="{{ $stat['text'] }} text-sm font-medium">{{ $stat['label'] }}</p>
                        <h2 class="text-2xl font-bold {{ $stat['text'] }}">{{ $stat['value'] }}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Tabel Permintaan Terbaru -->
    <div class="bg-white shadow border border-gray-200 p-6 rounded-xl mx-5 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600 text-[24px]">list_alt</span>
            5 Permintaan Terbaru
        </h3>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-700">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Judul Permintaan</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Tanggal</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($latestRequests as $key => $request)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full">
                                    {{ $key + 1 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 max-w-xs truncate"
                                title="{{ $request->judul_permintaan }}">
                                {{ $request->judul_permintaan }}
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="bg-indigo-100 text-indigo-800 text-xs font-medium px-2 py-1 rounded-full whitespace-nowrap">
                                    {{ $request->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($request->created_at)->translatedFormat('d M Y H:i') }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusClass = match (strtolower($request->status)) {
                                        'ditugaskan' => 'bg-yellow-100 text-yellow-800',
                                        'diproses' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="material-symbols-outlined text-4xl text-gray-300">inbox</span>
                                    <p>Belum ada permintaan terbaru</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
