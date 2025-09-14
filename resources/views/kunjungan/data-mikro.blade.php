@extends('layouts.tamu')

@section('content')
@section('title', 'Data Mikro | kunjungan')
<div style="background: linear-gradient(to bottom, #1D4ED8 0%, #072987 7%, transparent 7%, transparent 100%);">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header yang Diperbagus -->
        <div class="bg-white rounded-xl  p-6 mb-4 border border-blue-100">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 text-blue-600 rounded-lg p-3">
                        <span class="material-symbols-outlined text-3xl">database</span>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Data Mikro</h1>
                        <p class="text-gray-500 mt-1">Kumpulan dataset mikro untuk penelitian dan analisis</p>

                    </div>
                </div>
                <div class="text-center md:text-right">
                    <div class="text-3xl font-bold text-blue-700 text-center">{{ $dataMikro->total() }}</div>
                    <div class="text-gray-500 mt-1">Total Data Mikro</div>
                </div>
            </div>
        </div>
        <div class="bg-blue-100 text-blue-900 text-sm rounded p-2 mt-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-base">info</span>
            <span>
                Untuk informasi lebih lengkap, silakan kunjungi
                <a href="https://sliastik.example.com" target="_blank"
                    class="font-semibold underline hover:text-blue-700">
                    Sliastik
                </a> untuk Data Mikro dan Dataset.
            </span>
        </div>


        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="kategori" class="block text-sm font-semibold text-gray-700 mb-2">
                        <span class="material-symbols-outlined text-sm align-middle mr-1">filter_list</span>
                        Filter berdasarkan Kategori
                    </label>
                    <select name="kategori" id="kategori"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Semua Kategori --</option>
                        @foreach ($kategoriList as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">search</span>
                    Filter
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[70%_30%] gap-6">
            <!-- Konten kiri -->
            <div class="space-y-4">
                @forelse ($dataMikro as $data)
                    <div class="flex items-start bg-white border rounded shadow p-3 hover:bg-gray-50 transition">
                        @if ($data->gambar)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $data->gambar) }}" alt="{{ $data->judul }}"
                                    class="w-32 h-20 object-cover rounded">
                            </div>
                        @endif

                        <div class="ml-4 flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $data->judul }}</h3>
                            @if ($data->kategori)
                                <p class="text-xs text-green-500 italic mb-1">
                                    Kategori: {{ $data->kategori->nama_kategori }}
                                </p>
                            @endif
                            <p class="text-gray-600 text-sm mt-1 line-clamp-2">
                                {{ $data->deskripsi }}
                            </p>
                            <div class="mt-2">
                                <a href="{{ route('data-mikro.public.show', $data->id) }}"
                                    class="text-blue-600 text-sm hover:underline">Lihat Dataset</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">Belum ada data mikro tersedia.</p>
                @endforelse
            </div>

            <!-- Konten kanan -->
            <div class="space-y-6">
                <div class="text-blue-50 rounded-lg p-6">
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

                        <!-- Modal untuk banner -->
                        <div x-data="{ open: false, imageUrl: '' }">
                            <div x-show="open"
                                class="fixed inset-0 z-50 bg-black bg-opacity-70 flex items-center justify-center"
                                x-transition.opacity @click.self="open = false">
                                <div class="max-w-4xl mx-auto p-4">
                                    <img :src="imageUrl" class="rounded shadow-lg max-h-[90vh] w-auto mx-auto">
                                </div>
                            </div>

                            @foreach ($banners as $banner)
                                <div class="mt-4 cursor-pointer"
                                    @click="open = true; imageUrl = '{{ asset('storage/' . $banner->image_path) }}'">
                                    <img src="{{ asset('storage/' . $banner->image_path) }}"
                                        alt="{{ $banner->title }}"
                                        class="rounded shadow w-full object-cover hover:opacity-90 transition">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $dataMikro->appends(['kategori' => request('kategori')])->links() }}
        </div>

    </div>
</div>
@endsection
