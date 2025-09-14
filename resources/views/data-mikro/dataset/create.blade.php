@extends('layouts.petugas')

@section('content')
    <div class="max-w-full m-5 p-6 bg-white rounded shadow">
        <h1 class="text-xl font-bold mb-4">Tambah Dataset</h1>
        @if (session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 rounded border border-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 rounded border border-red-400">
                {{ session('error') }}
            </div>
        @endif


        <form action="{{ route('data-mikro.dataset.store', $microData->id) }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block font-medium mb-1">Judul Dataset</label>
                <input type="text" name="judul" class="w-full border rounded px-3 py-2" required>
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Level Penyajian</label>
                <input type="text" name="level_penyajian" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Harga</label>
                <input type="number" step="0.01" name="harga" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Ukuran File</label>
                <input type="text" name="ukuran_file" class="w-full border rounded px-3 py-2">
            </div>

            <div class="mb-4">
                <label class="block font-medium mb-1">Link (opsional)</label>
                <input type="url" name="link" class="w-full border rounded px-3 py-2">
            </div>

            <div class="flex justify-end">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </div>
@endsection
