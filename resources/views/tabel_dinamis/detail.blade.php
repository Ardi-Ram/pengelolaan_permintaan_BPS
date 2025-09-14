{{-- resources/views/tabel_dinamis/show.blade.php --}}
@extends('layouts.petugas')

@section('title', 'Detail Tabel Statistik')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-blue-50 to-slate-100 py-10 px-4 sm:px-6 lg:px-8">

        {{--  HEADER HERO  --}}
        <div class="max-w-6xl mx-auto">
            <div class="rounded-3xl overflow-hidden shadow-lg bg-gradient-to-r from-indigo-600 to-blue-500 text-white p-8">
                <div class="flex items-center gap-4">
                    <span class="material-symbols-outlined text-5xl">table_chart_view</span>
                    <div>
                        <h1 class="text-3xl font-extrabold tracking-tight">{{ $tabel->judul }}</h1>
                        <p class="mt-1 text-sm text-indigo-100">
                            Dibuat {{ $tabel->created_at->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{--  BODY  --}}
            <div class="bg-white mt-8 rounded-3xl shadow divide-y divide-gray-200">
                {{-- Ringkasan status --}}
                <section class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Status --}}
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-base">flag</span>Status
                        </h2>
                        @php
                            $statusColour =
                                [
                                    'antrian' => 'bg-gray-200 text-gray-700',
                                    'proses' => 'bg-yellow-100 text-yellow-800',
                                    'menunggu publish' => 'bg-blue-100 text-blue-800',
                                    'published' => 'bg-green-100 text-green-800',
                                ][$tabel->status] ?? 'bg-gray-100 text-gray-500';
                        @endphp
                        <span
                            class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold {{ $statusColour }}">
                            <span class="material-symbols-outlined text-sm align-middle">circle</span>
                            {{ ucfirst($tabel->status) }}
                        </span>
                    </div>

                    {{-- Deadline --}}
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase flex items-center gap-2 mb-2">
                            <span class="material-symbols-outlined text-base">schedule</span>Tanggal Rilis
                        </h2>
                        <p class="text-gray-800">
                            {{ $tabel->deadline ? \Carbon\Carbon::parse($tabel->deadline)->translatedFormat('d F Y') : '—' }}
                        </p>
                    </div>
                </section>

                {{-- Deskripsi --}}
                <section class="p-8">
                    <h2 class="text-sm font-semibold text-gray-500 uppercase flex items-center gap-2 mb-3">
                        <span class="material-symbols-outlined text-base">description</span>Deskripsi
                    </h2>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        {{ $tabel->deskripsi }}
                    </p>
                </section>

                {{-- Meta Info --}}
                <section class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2 flex gap-2 items-center">
                            <span class="material-symbols-outlined text-base">category</span>Kategori / Subjek
                        </h2>
                        <p class="text-gray-800 text-base font-medium">
                            <span class="inline-block bg-indigo-100 text-indigo-800 text-sm px-3 py-1 rounded-full mr-2">
                                {{ $tabel->kategori->nama_kategori ?? '-' }}
                            </span>
                            <span class="inline-block bg-indigo-50 text-indigo-600 text-sm px-3 py-1 rounded-full">
                                {{ $tabel->subject->nama_subject ?? '-' }}
                            </span>
                        </p>

                    </div>

                    <div>
                        <h2 class="text-sm font-semibold text-gray-500 uppercase mb-2 flex gap-2 items-center">
                            <span class="material-symbols-outlined text-base">groups</span>Pelanggan &amp; Pengolah
                        </h2>
                        <ul class="text-gray-800 space-y-1 text-sm">
                            <li><span class="font-medium">Petugas PST:</span> {{ $tabel->petugasPst->name ?? '—' }}</li>
                            <li><span class="font-medium">Pengolah Data:</span> {{ $tabel->pengolah->name ?? '—' }}</li>
                        </ul>
                    </div>
                </section>

                {{-- Alasan Penolakan / Verifikasi --}}
                @if ($tabel->alasan_penolakan || $tabel->verifikasi_pst === false)
                    <section class="p-8 bg-red-50 rounded-b-3xl">
                        <h2 class="text-sm font-semibold text-red-600 uppercase mb-2 flex gap-2 items-center">
                            <span class="material-symbols-outlined text-base">warning</span>
                            Catatan Penting
                        </h2>
                        @if ($tabel->alasan_penolakan)
                            <p class="text-red-700 whitespace-pre-line">{{ $tabel->alasan_penolakan }}</p>
                        @endif

                        @if ($tabel->verifikasi_pst === false)
                            <p class="text-red-700 whitespace-pre-line mt-2">{{ $tabel->catatan_verifikasi }}</p>
                        @endif
                    </section>
                @endif
            </div>

            {{-- LINK-LINK --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                @if ($tabel->link_hasil)
                    <a href="{{ $tabel->link_hasil }}" target="_blank"
                        class="flex items-center justify-between bg-green-100 border border-green-300 px-5 py-4 rounded-xl shadow hover:bg-green-200 transition">
                        <span class="flex items-center gap-3 text-green-800 font-medium">
                            <span class="material-symbols-outlined">link</span>Link Hasil Olahan
                        </span>
                        <span class="material-symbols-outlined text-green-600">open_in_new</span>
                    </a>
                @endif

                @if ($tabel->link_publish)
                    <a href="{{ $tabel->link_publish }}" target="_blank"
                        class="flex items-center justify-between bg-indigo-100 border border-indigo-300 px-5 py-4 rounded-xl shadow hover:bg-indigo-200 transition">
                        <span class="flex items-center gap-3 text-indigo-800 font-medium">
                            <span class="material-symbols-outlined">publish</span>Link Portal Publikasi
                        </span>
                        <span class="material-symbols-outlined text-indigo-600">open_in_new</span>
                    </a>
                @endif
            </div>

            {{-- BACK BTN --}}
            <div class="mt-10">
                <a href="{{ url()->previous() }}"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2 rounded-full shadow-sm transition">
                    <span class="material-symbols-outlined text-base">arrow_back</span>Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
