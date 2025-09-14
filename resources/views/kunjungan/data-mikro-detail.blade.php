@extends('layouts.tamu')

@section('content')
@section('title', 'Dataset | kunjungan')
<div style="background: linear-gradient(to bottom, #1D4ED8 0%, #072987 10%, transparent 10%, transparent 100%);">
    <div class="container mx-auto px-4 py-8">
        <div class="flex gap-6 px-4 py-8">
            <!-- Konten Utama -->
            <main class="w-[80%]">
                <!-- Header yang diperbagus, bg putih aksen biru -->
                <div class="bg-white rounded-xl  p-6 mb-8 border border-blue-100">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-blue-100 text-blue-600 rounded-lg p-3">
                            <span class="material-symbols-outlined text-3xl">analytics</span>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between w-full">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">{{ $dataMikro->judul }}</h1>
                                <p class="text-gray-500 mt-1">Dataset mikro untuk analisis dan penelitian</p>

                            </div>
                            <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-right">
                                <div class="text-3xl font-bold text-blue-700 text-center">
                                    {{ $dataMikro->items->count() }}
                                </div>
                                <div class="text-gray-500 mt-1">Total Dataset Tersedia</div>
                            </div>
                        </div>
                    </div>
                </div>
                <section>
                    <div class="flex items-center justify-between mb-6 p-2 bg-blue-100 rounded-lg">
                        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3 ">
                            <span class="material-symbols-outlined text-blue-600">folder_open</span>
                            Daftar Dataset
                        </h2>
                        <div class="text-sm text-gray-700">
                            Tersedia {{ $dataMikro->items->count() }} dataset
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse ($dataMikro->items as $item)
                            <article
                                class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200">
                                <a href="{{ $item->link }}" target="_blank" class="block p-6">
                                    <div class="flex items-start justify-between gap-4">

                                        <!-- Info Dataset -->
                                        <div class="flex items-start gap-4 flex-1">
                                            <div class="flex-shrink-0">
                                                <span
                                                    class="material-symbols-outlined text-3xl text-blue-600">dns</span>
                                            </div>

                                            <div class="min-w-0 flex-1">
                                                <h3 class="font-semibold text-gray-900 text-lg mb-2">{{ $item->judul }}
                                                </h3>

                                                @if ($item->level_penyajian)
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm text-gray-600">Level Penyajian:</span>
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            {{ $item->level_penyajian }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Info Harga & Ukuran -->
                                        <div class="text-right flex-shrink-0">
                                            @if ($item->harga)
                                                <p class="text-xl font-bold text-blue-600 mb-1">
                                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                </p>
                                            @endif

                                            @if ($item->ukuran_file)
                                                <p class="text-sm text-gray-500">
                                                    {{ $item->ukuran_file }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @empty
                            <div class="text-center py-12">
                                <div class="text-gray-400 mb-4">
                                    <span class="material-symbols-outlined text-6xl">folder_open</span>
                                </div>
                                <p class="text-gray-600">Belum ada dataset tersedia untuk data ini.</p>
                            </div>
                        @endforelse
                    </div>
                </section>
            </main>

            <!-- Sidebar -->
            <aside class="w-[20%]">
                <div class="space-y-6">

                    <!-- Link Groups -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        @foreach ($groups as $group)
                            <div class="mb-6 last:mb-0">
                                <h3
                                    class="font-semibold text-blue-800 text-sm uppercase tracking-wide mb-3 border-b border-blue-100 pb-2 text-center">
                                    {{ $group->name }}
                                </h3>

                                <nav class="space-y-2">
                                    @foreach ($group->links->sortBy('order') as $link)
                                        <a href="{{ $link->url }}" target="_blank"
                                            class="flex items-center justify-between p-2 text-sm text-blue-700 hover:bg-blue-50 rounded transition-colors duration-150">
                                            <span>{{ $link->label }}</span>
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endforeach
                                </nav>
                            </div>
                        @endforeach
                    </div>

                    <!-- Banner Section -->
                    @if ($banners->count() > 0)
                        <div x-data="{ modalOpen: false, selectedImage: '' }">
                            <div class="space-y-4">
                                @foreach ($banners as $banner)
                                    <div class="cursor-pointer group"
                                        @click="modalOpen = true; selectedImage = '{{ asset('storage/' . $banner->image_path) }}'">
                                        <img src="{{ asset('storage/' . $banner->image_path) }}"
                                            alt="{{ $banner->title }}"
                                            class="w-full rounded-lg shadow-sm hover:shadow-md transition-all duration-200 group-hover:scale-[1.02]">
                                    </div>
                                @endforeach
                            </div>

                            <!-- Modal -->
                            <div x-show="modalOpen" x-transition.opacity
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75"
                                @click.self="modalOpen = false" @keydown.escape.window="modalOpen = false">
                                <div class="relative max-w-5xl mx-4">
                                    <button @click="modalOpen = false"
                                        class="absolute -top-4 -right-4 bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                    <img :src="selectedImage" class="rounded-lg shadow-2xl max-h-[90vh] w-auto">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection
