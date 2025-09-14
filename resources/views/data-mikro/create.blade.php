@extends('layouts.petugas')

@section('content')
    <div class="p-6 max-w-full m-5">
        <h2 class="text-xl font-semibold mb-4">Tambah Micro Data</h2>

        <form action="{{ route('data-mikro.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-4 bg-white p-6 rounded shadow">
            @csrf

            <div>
                <label class="block font-medium mb-1">Judul</label>
                <input type="text" name="judul" value="{{ old('judul') }}" class="w-full border-gray-300 rounded"
                    required>
            </div>

            <div>
                <label class="block font-medium mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border-gray-300 rounded" required>
                    <option value="">Pilih Kategori</option>
                    @foreach ($kategori as $kat)
                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-medium mb-1">Deskripsi</label>
                <textarea name="deskripsi" class="w-full border-gray-300 rounded" rows="4">{{ old('deskripsi') }}</textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Gambar</label>
                <input type="file" name="gambar" class="w-full border-gray-300 rounded">
            </div>

            <div class="pt-4">
                <button class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700" type="submit">Simpan</button>
                <a href="{{ route('data-mikro.index') }}" class="ml-2 text-gray-600 hover:underline">Batal</a>
            </div>
        </form>
    </div>
@endsection
