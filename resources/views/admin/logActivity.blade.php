@extends('layouts.admin')

@section('content')
    <div class="flex items-center space-x-4 m-5">
        <a href="{{ route('admin.index') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.index') ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}">
            Dashboard Admin
        </a>
        <a href="{{ route('admin.log-aktivitas') }}"
            class="px-4 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.log-aktivitas') ? 'bg-blue-600 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-800' }}">
            Log Aktivitas
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 m-5">
        {{-- Log Aktivitas --}}
        <div class="w-full lg:w-2/3 bg-white rounded-lg border border-gray-300">
            <h1 class="text-xl font-bold mb-6 border-b border-gray-300 p-5">Log Aktivitas</h1>

            <div class="m-5">
                @foreach ($logsByDate as $date => $logs)
                    <div class="p-5">
                        <h2 class="text-base font-semibold text-gray-500 mb-4">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y') }}
                        </h2>

                        <ol class="relative border-s border-gray-200">
                            @foreach ($logs as $log)
                                <li class="mb-10 ms-6">
                                    <span
                                        class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-8 ring-white">
                                        <svg class="w-2.5 h-2.5 text-blue-800" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                                        </svg>
                                    </span>
                                    <h3 class="mb-1 text-base font-semibold text-gray-900">
                                        {{ $log->user->name }}
                                    </h3>
                                    <time class="block mb-2 text-sm text-gray-400">
                                        {{ $log->created_at->format('H:i') }}
                                    </time>
                                    <p class="text-sm text-gray-600">
                                        {{ $log->activity }}
                                    </p>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Rekap --}}
        <div class="w-full lg:w-1/3 bg-white rounded-lg border border-gray-300 p-6">
            <h2 class="text-lg font-bold mb-4">Rekap Aktivitas</h2>

            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Permintaan oleh Petugas PST:</h3>
                <ul class="space-y-1">
                    @foreach ($rekapPetugas as $petugas)
                        <li class="text-sm text-gray-600">{{ $petugas->name }}:
                            <strong>{{ $petugas->permintaan_data_count }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Hasil Olahan oleh Pengolah Data:</h3>
                <ul class="space-y-1">
                    @foreach ($rekapPengolah as $pengolah)
                        <li class="text-sm text-gray-600">{{ $pengolah->name }}:
                            <strong>{{ $pengolah->jumlah_hasil_olahan }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
