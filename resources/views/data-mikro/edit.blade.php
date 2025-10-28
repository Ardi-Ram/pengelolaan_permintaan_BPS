@extends('layouts.app')
@section('title', 'Edit Data Mikro')
@section('content')
    <div class="m-5 p-6  bg-white rounded shadow">
        <h2 class="text-xl font-semibold mb-6">Edit Micro Data</h2>

        <form action="{{ route('data-mikro.update', $micro_data->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-5">
            @csrf @method('PUT')

            {{-- Judul --}}
            <div class="flex items-center gap-4">
                <label class="w-1/5 font-medium">Judul</label>
                <input type="text" name="judul" value="{{ old('judul', $micro_data->judul) }}"
                    class="flex-1 border-gray-300 rounded" required>
            </div>

            {{-- Kategori --}}
            <div class="flex items-center gap-4">
                <label class="w-1/5 font-medium">Kategori</label>
                <select name="kategori_id" class="flex-1 border-gray-300 rounded" required>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ $micro_data->kategori_id == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Deskripsi --}}
            <div class="flex items-start gap-4">
                <label class="w-1/5 font-medium">Deskripsi</label>
                <textarea name="deskripsi" class="flex-1 border-gray-300 rounded" rows="4">{{ old('deskripsi', $micro_data->deskripsi) }}</textarea>
            </div>

            {{-- Gambar --}}
            <div class="flex items-start gap-4">
                <label class="w-1/5 font-medium">Gambar Saat Ini</label>
                <div class="flex-1">
                    @if ($micro_data->gambar)
                        <img src="{{ asset('storage/' . $micro_data->gambar) }}"
                            class="w-32 h-32 object-cover rounded mb-2">
                    @else
                        <p class="italic text-gray-500">Tidak ada gambar</p>
                    @endif
                    <input type="file" name="gambar" class="w-full border-gray-300 rounded">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="flex items-center gap-4 pt-4">
                <label class="w-1/5"></label>
                <div>
                    <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700" type="submit">
                        Update
                    </button>
                    <a href="{{ route('data-mikro.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
                </div>
            </div>
        </form>
    </div>
@endsection
