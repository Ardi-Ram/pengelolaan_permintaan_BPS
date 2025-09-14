@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="w-full px-6">

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Links & Banner</h1>
                            <p class="text-sm text-gray-600 mt-1">Kelola tautan navigasi, grup, dan banner promosi</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-green-50 rounded-full p-3">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-8">
                <div class="flex-1 min-w-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Manajemen Link</h2>
                            <div class="flex gap-3">
                                <button data-modal-target="#modal-add-group"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Group
                                </button>
                                <button data-modal-target="#modal-add-link"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah Link
                                </button>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @foreach ($groups as $group)
                                <div class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm">
                                    <div
                                        class="flex items-center justify-between bg-gradient-to-r from-gray-50 to-gray-100 px-5 py-4 border-b border-gray-200">
                                        <div>
                                            <h3 class="font-semibold text-gray-900 text-lg">{{ $group->name }}</h3>
                                            <p class="text-sm text-gray-500 mt-1">
                                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                                                    </path>
                                                </svg>
                                                {{ $group->links->count() }} link
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('admin.links.group.up', $group) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button
                                                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-md transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.links.group.down', $group) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button
                                                    class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded-md transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <button data-modal-target="#modal-edit-group-{{ $group->id }}"
                                                class="px-3 py-1.5 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded-md text-sm font-medium transition-colors">
                                                Edit
                                            </button>
                                            <form action="{{ route('admin.links.group.destroy', $group) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus group ini?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="px-3 py-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md text-sm font-medium transition-colors">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="p-4">
                                        <div class="space-y-2">
                                            @foreach ($group->links->sortBy('order') as $link)
                                                <div
                                                    class="flex items-center justify-between bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg px-4 py-3 transition-colors">
                                                    <a href="{{ $link->url }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2 flex-1 min-w-0">
                                                        <svg class="w-4 h-4 flex-shrink-0" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                            </path>
                                                        </svg>
                                                        <span class="truncate">{{ $link->label }}</span>
                                                    </a>
                                                    <div class="flex items-center gap-1">
                                                        <form action="{{ route('admin.links.link.up', $link) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button
                                                                class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('admin.links.link.down', $link) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            <button
                                                                class="p-1.5 text-gray-500 hover:text-gray-700 hover:bg-gray-200 rounded transition-colors">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                            </button>
                                                        </form>
                                                        <button data-modal-target="#modal-edit-link-{{ $link->id }}"
                                                            class="px-2 py-1 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded text-xs font-medium transition-colors">
                                                            Edit
                                                        </button>
                                                        <form action="{{ route('admin.links.link.destroy', $link) }}"
                                                            method="POST" class="inline"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus link ini?')">
                                                            @csrf @method('DELETE')
                                                            <button
                                                                class="px-2 py-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded text-xs font-medium transition-colors">
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>

                                                {{-- Modal Edit Link --}}
                                                <div id="modal-edit-link-{{ $link->id }}"
                                                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                                    <form action="{{ route('admin.links.link.update', $link) }}"
                                                        method="POST"
                                                        class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                                                        @csrf
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Link</h3>
                                                        <div class="space-y-4">
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                                                <input name="label" value="{{ $link->label }}"
                                                                    placeholder="Masukkan label link"
                                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                            </div>
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                                                <input name="url" value="{{ $link->url }}"
                                                                    placeholder="https://example.com"
                                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                            </div>

                                                            <input type="hidden" name="link_group_id"
                                                                value="{{ $group->id }}">
                                                        </div>
                                                        <div class="flex justify-end gap-3 mt-6">
                                                            <button type="button" data-modal-close
                                                                class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                                                Batal
                                                            </button>
                                                            <button
                                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                {{-- Modal Edit Group --}}
                                <div id="modal-edit-group-{{ $group->id }}"
                                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                    <form action="{{ route('admin.links.group.update', $group) }}" method="POST"
                                        class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                                        @csrf
                                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Group</h3>
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama
                                                    Group</label>
                                                <input name="name" value="{{ $group->name }}"
                                                    placeholder="Masukkan nama group"
                                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            </div>

                                        </div>
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button type="button" data-modal-close
                                                class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                                Batal
                                            </button>
                                            <button
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        {{-- Modal Tambah Group --}}
                        <div id="modal-add-group"
                            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                            <form action="{{ route('admin.links.group.store') }}" method="POST"
                                class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                                @csrf
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Group Baru</h3>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Group</label>
                                    <input name="name" placeholder="Masukkan nama group"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" data-modal-close
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                        Batal
                                    </button>
                                    <button
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Modal Tambah Link --}}
                        <div id="modal-add-link"
                            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                            <form action="{{ route('admin.links.link.store') }}" method="POST"
                                class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                                @csrf
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Link Baru</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Label</label>
                                        <input name="label" placeholder="Masukkan label link"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                                        <input name="url" placeholder="https://example.com"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                                        <select name="link_group_id"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                            @foreach ($groups as $group)
                                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" data-modal-close
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                        Batal
                                    </button>
                                    <button
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Banner --}}
                <div class="w-80 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 ">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Banner</h2>
                            <button data-modal-target="#modal-add-banner"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Banner
                            </button>
                        </div>

                        <div class="space-y-4 ">
                            @foreach ($banners as $banner)
                                <div
                                    class="border border-gray-200 rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow">
                                    <div class="aspect-video">
                                        <img src="{{ asset('storage/' . $banner->image_path) }}"
                                            alt="{{ $banner->title }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="p-3">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-medium text-gray-900 text-sm truncate flex-1 mr-2">
                                                {{ $banner->title }}</h4>
                                            <div class="flex items-center gap-1">
                                                <form action="{{ route('admin.links.banner.up', $banner) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button
                                                        class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.links.banner.down', $banner) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <button
                                                        class="p-1 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.links.banner.destroy', $banner) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus banner ini?')">
                                                    @csrf @method('DELETE')
                                                    <button
                                                        class="p-1 text-red-600 hover:text-red-700 hover:bg-red-50 rounded transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                            </path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Modal Tambah Banner --}}
                        <div id="modal-add-banner"
                            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                            <form action="{{ route('admin.links.banner.store') }}" method="POST"
                                enctype="multipart/form-data" class="bg-white rounded-xl p-6 w-full max-w-md shadow-xl">
                                @csrf
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tambah Banner Baru</h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Judul Banner</label>
                                        <input name="title" placeholder="Masukkan judul banner"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Banner</label>
                                        <input type="file" name="image" accept="image/*"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                    </div>
                                </div>
                                <div class="flex justify-end gap-3 mt-6">
                                    <button type="button" data-modal-close
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium transition-colors">
                                        Batal
                                    </button>
                                    <button
                                        class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal functionality
                document.querySelectorAll('[data-modal-target]').forEach(button => {
                    button.addEventListener('click', function() {
                        let modal = document.querySelector(this.getAttribute('data-modal-target'));
                        if (modal) {
                            modal.classList.remove('hidden');
                            document.body.style.overflow = 'hidden';
                        }
                    });
                });

                document.querySelectorAll('[data-modal-close]').forEach(button => {
                    button.addEventListener('click', function() {
                        let modal = this.closest('.fixed.inset-0');
                        if (modal) {
                            modal.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }
                    });
                });

                // Close modal when clicking outside
                document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            this.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        }
                    });
                });

                // Close modal with Escape key
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        document.querySelectorAll('.fixed.inset-0:not(.hidden)').forEach(modal => {
                            modal.classList.add('hidden');
                            document.body.style.overflow = 'auto';
                        });
                    }
                });
            });
        </script>
    @endpush
@endsection
