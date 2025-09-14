@extends('layouts.tamu')

@section('content')
@section('title', 'Monitoring status permintaan dat | kunjungan')

<section class="bg-[linear-gradient(to_right,#1e3a8a,#2563eb,#3b82f6)] text-white py-16 px-6 relative overflow-hidden">
    <div class="relative max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-[70%_30%] gap-12 items-center lg:px-20">
            <!-- Teks -->
            <div class="relative z-10">
                <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                    <span class="text-sm font-medium">✓ Sistem Terintegrasi</span>
                </div>
                <div
                    class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left mb-6 space-y-4 sm:space-y-0 sm:space-x-4">
                    <!-- Logo -->
                    <img src="/images/bps-logo.png" alt="Logo BPS" class="w-24 h-24 p-2 mx-auto sm:mx-0 sm:mr-4" />

                    <!-- Text -->
                    <div>
                        <h2 class="text-2xl sm:text-4xl md:text-5xl font-bold leading-tight">
                            Layanan Monitoring Status <span class="text-white">Permintaan Data</span>
                        </h2>
                        <p class="text-sm sm:text-base text-white mt-2">
                            BPS Provinsi Kepulauan Bangka Belitung
                        </p>
                    </div>
                </div>


                <p class="text-xl text-white mb-8 leading-relaxed max-w-xl">
                    Akses dan pantau status permintaan data statistik Anda dengan mudah melalui portal pelayanan digital
                    yang terintegrasi dan aman.
                </p>

                {{-- Tambahan tombol dengan icon --}}
                <div class="flex gap-4">
                    <a href="{{ route('tabel-dinamis.portal') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-transparent border border-white text-white font-semibold rounded-full hover:bg-white hover:text-blue-600 transition">
                        <span class="material-symbols-outlined text-[20px]">table_chart</span>
                        Tabel Statistik
                    </a>
                    <a href="{{ route('data-mikro.public.index') }}"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-transparent border border-white text-white font-semibold rounded-full hover:bg-white hover:text-blue-600 transition">
                        <span class="material-symbols-outlined text-[20px]">dataset</span>
                        Data Mikro
                    </a>
                </div>
            </div>



            <!-- Gambar -->
            <div class="hidden md:flex justify-center items-center bg-blue-500 rounded-full">
                <img src="{{ asset('images/search.png') }}" alt="Data Search Icon" class="w-100 h-100 object-contain" />
            </div>
        </div>
    </div>
</section>



<!-- Penjelasan Layanan -->
<div class="py-6">
    <div class="max-w-7xl mx-auto rounded-lg">
        <div class="bg-white rounded-lg p-6 shadow-sm">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center border-b-2 border-gray-200 p-2">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Tentang Layanan Ini
            </h3>

            <div class="grid md:grid-cols-2 gap-6 text-gray-700">
                <div>
                    <h4 class="font-semibold mb-2">Apa itu Sistem Pelayanan Data BPS?</h4>
                    <p class="text-sm leading-relaxed">
                        Sistem ini merupakan portal resmi BPS untuk memantau status permintaan data statistik yang telah
                        diajukan.
                        Anda cukup memasukkan <strong>kode transaksi</strong> untuk mengetahui sejauh mana proses
                        pengolahan data Anda.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-2">Petunjuk Penggunaan:</h4>
                    <ol class="text-sm space-y-2">
                        <li class="flex items-start">
                            <span
                                class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">1</span>
                            <span>Pastikan Anda telah memiliki kode transaksi dari pengajuan data.</span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">2</span>
                            <span>Masukkan kode transaksi pada kolom pencarian di atas.</span>
                        </li>
                        <li class="flex items-start">
                            <span
                                class="bg-blue-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs mr-3 mt-0.5">3</span>
                            <span>Tekan tombol "Cari" untuk melihat status pengolahan data Anda.</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="max-w-7xl mx-auto px-4 lg:px-0 py-8 ">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 rounded-lg px-0 ">

        <!-- Area Utama -->
        <div class="lg:col-span-9 px-4  lg:px-0">
            <div class="bg-white rounded-lg  overflow-hidden">
                <!-- Header -->
                <div class="bg-blue-700 px-6 py-6">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Lacak Status Permintaan Data
                    </h1>
                    <p class="text-blue-100 mt-2">
                        Masukkan kode transaksi yang dikirim ke email Anda untuk melihat status dan riwayat permintaan
                        data statistik
                    </p>
                </div>

                <!-- Form Pencarian -->
                <div class="">
                    @if (session('error'))
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                        <form action="{{ route('kunjungan.cari') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label for="kode_transaksi" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Kode Transaksi Permintaan Data
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                        </svg>
                                    </div>
                                    <input type="text" id="kode_transaksi" name="kode_transaksi"
                                        placeholder="Contoh: TXN-2024-001234"
                                        class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                                        value="{{ old('kode_transaksi') }}" required>
                                </div>
                                <p class="mt-2 text-xs text-gray-500">
                                    Kode transaksi telah dikirimkan ke email Anda saat permintaan data anda diProses
                                    atau bisa meminta kode transaksi ke petugas pelayanan statistik terpadu
                                </p>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 text-white py-3 px-6 rounded-lg font-semibold transition duration-200 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Lacak Status Permintaan
                            </button>
                        </form>
                    </div>

                    <!-- Hasil Pencarian -->
                    @isset($pemilik)
                        <!-- Info Pemohon -->
                        <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Data Pemohon
                                </h3>
                            </div>
                            <div class="p-8 bg-white rounded-xl shadow-sm border border-gray-100">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                    <!-- Kolom Kiri -->
                                    <div class="space-y-6">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                                                <span class="text-blue-600 text-lg">👤</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-500 mb-1">Nama Lengkap</h4>
                                                <p class="text-base font-semibold text-gray-900">
                                                    {{ $pemilik->nama_pemilik }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                                                <span class="text-green-600 text-lg">🏢</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-500 mb-1">Instansi/Organisasi</h4>
                                                <p class="text-base font-semibold text-gray-900">{{ $pemilik->instansi }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="space-y-6">
                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                                <span class="text-purple-600 text-lg">📧</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-500 mb-1">Email</h4>
                                                <p class="text-base font-semibold text-gray-900 break-all">
                                                    {{ $pemilik->email }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-start gap-4">
                                            <div
                                                class="flex-shrink-0 w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                                                <span class="text-orange-600 text-lg">📱</span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-500 mb-1">No. WhatsApp</h4>
                                                <p class="text-base font-semibold text-gray-900">{{ $pemilik->no_wa }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Riwayat Permintaan -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    Riwayat Permintaan Data
                                </h3>
                                <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    {{ count($permintaan) }} permintaan
                                </span>
                            </div>

                            @if (count($permintaan) > 0)
                                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-blue-600">
                                                <tr>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                        Judul Permintaan</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                        Status</th>
                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                        Kategori Data</th>

                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                        Tanggal Dibuat</th>

                                                    <th
                                                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                                                        Unduh
                                                    </th>


                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach ($permintaan as $data)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                                            {{ $data->judul_permintaan }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm">
                                                            @if ($data->status === 'selesai')
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    Selesai
                                                                </span>
                                                            @elseif($data->status === 'proses')
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                                            clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    Sedang Diproses
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                                    <svg class="w-3 h-3 mr-1" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z"
                                                                            clip-rule="evenodd"></path>
                                                                    </svg>
                                                                    {{ ucfirst($data->status) }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                        <td class="px-6 py-4 text-sm text-gray-500">
                                                            {{ $data->kategori->nama_kategori ?? 'Belum dikategorikan' }}
                                                        </td>

                                                        <td class="px-6 py-4 text-sm text-gray-500">
                                                            {{ $data->created_at->format('d/m/Y H:i') }}
                                                        </td>
                                                        <td class="px-6 py-4 text-sm">
                                                            @if ($data->status === 'selesai')
                                                                @if ($data->hasilOlahan && $data->hasilOlahan->path_file && Storage::exists('public/' . $data->hasilOlahan->path_file))
                                                                    <a href="{{ asset('storage/' . $data->hasilOlahan->path_file) }}"
                                                                        download
                                                                        class="inline-flex items-center px-3 py-1 rounded text-xs text-white bg-blue-600 hover:bg-blue-700">
                                                                        <svg class="w-4 h-4 mr-1" fill="none"
                                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round" stroke-width="2"
                                                                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                                                                        </svg>
                                                                        Download
                                                                    </a>
                                                                @else
                                                                    <span class="text-red-500 text-xs italic">File tidak
                                                                        tersedia</span>
                                                                @endif
                                                            @else
                                                                <span class="text-gray-400 text-xs italic">Belum
                                                                    tersedia</span>
                                                            @endif
                                                        </td>

                                                        </td>


                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                                    <img src="/images/empty.png" class="mx-auto" alt="Empty" width="200"
                                        height="200">
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum Ada Permintaan</h3>
                                    <p class="mt-1 text-sm text-gray-500">Data permintaan belum tersedia untuk kode
                                        transaksi ini.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="mt-8 text-center py-12 bg-white rounded-lg border border-gray-200">
                            <img src="{{ asset('images/cari-kode.png') }}" alt="Cari Kode"
                                class="mx-auto h-[250px] w-[250px] object-contain text-gray-400">

                            <h3 class="mt-4 text-lg font-medium text-gray-900">Mulai Lacak Permintaan Anda</h3>

                            <p class="mt-2 text-sm text-gray-500 max-w-md mx-auto">
                                Masukkan kode transaksi pada form di atas untuk melihat status dan detail permintaan data
                                statistik Anda.
                            </p>
                        </div>

                    @endisset
                </div>

                <!-- Footer Info -->

            </div>
        </div>

        <!-- Sidebar Informasi -->
        <div class="lg:col-span-3 space-y-6">


            <div class="text-blue-50 rounded-lg    p-6">


                <div class="mt-6">


                    <div class="bg-white shadow rounded-lg p-4 space-y-4">
                        @foreach ($groups as $group)
                            <div>
                                <div
                                    class="text-center font-semibold text-blue-800 text-sm uppercase tracking-wide mb-2">
                                    {{ $group->name }}
                                </div>
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($group->links->sortBy('order') as $link)
                                        <li>
                                            <a href="{{ $link->url }}" target="_blank"
                                                class="flex justify-between items-center py-1 text-sm text-blue-700 hover:underline">
                                                {{ $link->label }}
                                                {{-- Optional: icon panah --}}
                                                <svg class="w-3 h-3 text-blue-500 ml-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>


                    <!-- Modal untuk menampilkan banner -->
                    <div x-data="{ open: false, imageUrl: '' }">
                        <!-- Modal Background -->
                        <div x-show="open"
                            class="fixed inset-0 z-50 bg-black bg-opacity-70 flex items-center justify-center"
                            x-transition.opacity @click.self="open = false">
                            <div class="max-w-4xl mx-auto p-4">
                                <img :src="imageUrl" class="rounded shadow-lg max-h-[90vh] w-auto mx-auto">
                            </div>
                        </div>

                        <!-- Banner thumbnails -->
                        @foreach ($banners as $banner)
                            <div class="mt-4 cursor-pointer"
                                @click="open = true; imageUrl = '{{ asset('storage/' . $banner->image_path) }}'">
                                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}"
                                    class="rounded shadow w-full object-cover hover:opacity-90 transition">
                            </div>
                        @endforeach
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>



@endsection
