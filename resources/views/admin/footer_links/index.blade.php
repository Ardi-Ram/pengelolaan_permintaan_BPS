@extends('layouts.admin')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-6">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Kelola Footer Links</h1>
                        <p class="text-sm text-gray-600 mt-1">Kelola link navigasi dan grup di bagian footer website</p>
                    </div>
                    <div class="flex gap-2">
                        <button data-modal-target="#modal-add-group"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            + Tambah Group
                        </button>
                        <button data-modal-target="#modal-add-link"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            + Tambah Link
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Groups List -->
        @foreach ($groups as $group)
            <div class="bg-white border rounded-lg mb-4 shadow-sm">
                <!-- Group Header -->
                <div class="bg-white px-4 py-3 border-b flex items-center justify-between">
                    <div>
                        <h2 class="font-semibold text-gray-900">{{ $group->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $group->links->count() }} link</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Order buttons -->
                        <div class="flex border rounded overflow-hidden">
                            <form action="{{ route('admin.footer_links.group.up', $group) }}" method="POST">
                                @csrf
                                <button class="px-2 py-1 text-gray-600 hover:bg-gray-100">▲</button>
                            </form>
                            <form action="{{ route('admin.footer_links.group.down', $group) }}" method="POST">
                                @csrf
                                <button class="px-2 py-1 text-gray-600 hover:bg-gray-100 border-l">▼</button>
                            </form>
                        </div>

                        <button data-modal-target="#modal-edit-group-{{ $group->id }}"
                            class="text-blue-600 hover:text-blue-800 text-sm px-2 py-1">
                            Edit
                        </button>

                        <form action="{{ route('admin.footer_links.group.destroy', $group) }}" method="POST"
                            class="inline">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Hapus group ini?')"
                                class="text-red-600 hover:text-red-800 text-sm px-2 py-1">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Links List -->
                <div class="p-4">
                    @if ($group->links->isEmpty())
                        <p class="text-gray-500 text-center py-4">Belum ada link dalam group ini</p>
                    @else
                        <div class="space-y-2">
                            @foreach ($group->links->sortBy('order') as $link)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded border">
                                    <div>
                                        <a href="{{ $link->url }}" target="_blank"
                                            class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                            {{ $link->label }}
                                        </a>
                                        <p class="text-xs text-gray-500">{{ $link->url }}</p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <!-- Order buttons -->
                                        <div class="flex border rounded overflow-hidden">
                                            <form action="{{ route('admin.footer_links.link.up', $link) }}" method="POST">
                                                @csrf
                                                <button class="px-2 py-1 text-gray-600 hover:bg-gray-100 text-xs">▲</button>
                                            </form>
                                            <form action="{{ route('admin.footer_links.link.down', $link) }}"
                                                method="POST">
                                                @csrf
                                                <button
                                                    class="px-2 py-1 text-gray-600 hover:bg-gray-100 border-l text-xs">▼</button>
                                            </form>
                                        </div>

                                        <button data-modal-target="#modal-edit-link-{{ $link->id }}"
                                            class="text-blue-600 hover:text-blue-800 text-xs px-2 py-1">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.footer_links.link.destroy', $link) }}" method="POST"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('Hapus link ini?')"
                                                class="text-red-600 hover:text-red-800 text-xs px-2 py-1">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <!-- Modal Edit Link -->
                                <div id="modal-edit-link-{{ $link->id }}"
                                    class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                    <div class="bg-white rounded-lg w-96 max-w-full mx-4">
                                        <form action="{{ route('admin.footer_links.link.update', $link) }}" method="POST">
                                            @csrf
                                            <div class="p-4 border-b">
                                                <h3 class="font-semibold">Edit Link</h3>
                                            </div>
                                            <div class="p-4 space-y-3">
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">Label</label>
                                                    <input name="label" value="{{ $link->label }}"
                                                        class="w-full border rounded px-3 py-2" required>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-medium mb-1">URL</label>
                                                    <input name="url" value="{{ $link->url }}"
                                                        class="w-full border rounded px-3 py-2" required>
                                                </div>

                                                <input type="hidden" name="link_group_id" value="{{ $group->id }}">
                                            </div>
                                            <div class="p-4 border-t flex justify-end gap-2">
                                                <button type="button" data-modal-close
                                                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
                                                    Batal
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal Edit Group -->
            <div id="modal-edit-group-{{ $group->id }}"
                class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg w-96 max-w-full mx-4">
                    <form action="{{ route('admin.footer_links.group.update', $group) }}" method="POST">
                        @csrf
                        <div class="p-4 border-b">
                            <h3 class="font-semibold">Edit Group</h3>
                        </div>
                        <div class="p-4 space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-1">Nama Group</label>
                                <input name="name" value="{{ $group->name }}" class="w-full border rounded px-3 py-2"
                                    required>
                            </div>

                        </div>
                        <div class="p-4 border-t flex justify-end gap-2">
                            <button type="button" data-modal-close
                                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

        <!-- Modal Tambah Group -->
        <div id="modal-add-group" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg w-96 max-w-full mx-4">
                <form action="{{ route('admin.footer_links.group.store') }}" method="POST">
                    @csrf
                    <div class="p-4 border-b">
                        <h3 class="font-semibold">Tambah Group</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Group</label>
                            <input name="name" class="w-full border rounded px-3 py-2" required>
                        </div>

                    </div>
                    <div class="p-4 border-t flex justify-end gap-2">
                        <button type="button" data-modal-close class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Tambah Link -->
        <div id="modal-add-link"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg w-96 max-w-full mx-4">
                <form action="{{ route('admin.footer_links.link.store') }}" method="POST">
                    @csrf
                    <div class="p-4 border-b">
                        <h3 class="font-semibold">Tambah Link</h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-sm font-medium mb-1">Label</label>
                            <input name="label" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">URL</label>
                            <input name="url" class="w-full border rounded px-3 py-2" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Group</label>
                            <select name="footer_link_group_id" class="w-full border rounded px-3 py-2" required>
                                <option value="">Pilih Group</option>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="p-4 border-t flex justify-end gap-2">
                        <button type="button" data-modal-close class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Open modal
                document.querySelectorAll('[data-modal-target]').forEach(button => {
                    button.addEventListener('click', function() {
                        const modal = document.querySelector(this.getAttribute('data-modal-target'));
                        if (modal) modal.classList.remove('hidden');
                    });
                });

                // Close modal
                document.querySelectorAll('[data-modal-close]').forEach(button => {
                    button.addEventListener('click', function() {
                        const modal = this.closest('.fixed.inset-0');
                        if (modal) modal.classList.add('hidden');
                    });
                });

                // Close modal when clicking outside
                document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) this.classList.add('hidden');
                    });
                });
            });
        </script>
    @endpush
@endsection
