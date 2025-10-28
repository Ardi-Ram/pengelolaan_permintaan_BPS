@extends('layouts.app')
@section('title', 'Daftar Mikro Data')
@section('content')
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-xl font-semibold">Daftar Micro Data</h1>
            <a href="{{ route('data-mikro.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Tambah Micro Data
            </a>
        </div>

        @if (session('success'))
            <div class="mb-4 text-green-700 bg-green-100 p-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-100 text-xs text-gray-700 uppercase">
                    <tr>
                        <th class="px-4 py-3">Judul</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Deskripsi</th>
                        <th class="px-4 py-3">Gambar</th>
                        <th class="px-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">{{ $item->judul }}</td>
                            <td class="px-4 py-3">{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($item->deskripsi, 60) }}</td>
                            <td class="px-4 py-3">
                                @if ($item->gambar)
                                    <img src="{{ asset('storage/' . $item->gambar) }}"
                                        class="w-12 h-12 object-cover rounded">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex items-center space-x-2">
                                {{-- Detail --}}
                                <a href="{{ route('data-mikro.show', $item->id) }}"
                                    class="p-2 border border-blue-500 text-blue-500 rounded hover:bg-blue-50"
                                    title="Detail">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('data-mikro.edit', $item->id) }}"
                                    class="p-2 border border-yellow-500 text-yellow-500 rounded hover:bg-yellow-50"
                                    title="Edit">
                                    <span class="material-symbols-outlined text-base">edit</span>
                                </a>

                                {{-- Hapus --}}
                                <form action="{{ route('data-mikro.destroy', $item->id) }}" method="POST"
                                    class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="delete-btn p-2 border border-red-500 text-red-500 rounded hover:bg-red-50"
                                        title="Hapus">
                                        <span class="material-symbols-outlined text-base">delete</span>
                                    </button>
                                </form>

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-500">Tidak ada data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $data->links('pagination::tailwind') }}
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".delete-btn").forEach(function(btn) {
                btn.addEventListener("click", function() {
                    let form = this.closest("form");

                    Swal.fire({
                        title: "Yakin ingin menghapus?",
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
