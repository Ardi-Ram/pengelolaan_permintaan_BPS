@extends('layouts.admin')

@section('content')
    <div class="max-w-xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-xl font-bold mb-4">Edit Dataset</h1>

        <form action="{{ route('micro-data-item.update', $item->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block font-medium mb-1">Judul Dataset</label>
                <input type="text" name="judul" class="w-full border rounded px-3 py-2"
                    value="{{ old('judul', $item->judul) }}" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Micro Data</label>
                <select name="micro_data_id" class="w-full border rounded px-3 py-2" required>
                    <option value="">-- Pilih --</option>
                    @foreach ($microDataList as $data)
                        <option value="{{ $data->id }}" {{ $item->micro_data_id == $data->id ? 'selected' : '' }}>
                            {{ $data->judul }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Level Penyajian</label>
                <input type="text" name="level_penyajian" class="w-full border rounded px-3 py-2"
                    value="{{ old('level_penyajian', $item->level_penyajian) }}">
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Harga</label>
                <input type="number" step="0.01" name="harga" class="w-full border rounded px-3 py-2"
                    value="{{ old('harga', $item->harga) }}">
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Ukuran File</label>
                <input type="text" name="ukuran_file" class="w-full border rounded px-3 py-2"
                    value="{{ old('ukuran_file', $item->ukuran_file) }}">
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Link (opsional)</label>
                <input type="url" name="link" class="w-full border rounded px-3 py-2"
                    value="{{ old('link', $item->link) }}">
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
@endsection
