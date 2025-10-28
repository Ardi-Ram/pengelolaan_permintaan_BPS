@extends('layouts.app')
@section('title', 'Detail Data Mikro')
@section('content')
    <div class="max-w-full m-5 p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-4">{{ $microData->judul }}</h1>

        <div class="grid md:grid-cols-2 gap-6 mb-6">
            <div>
                <p class="mb-2"><span class="font-semibold">Deskripsi:</span></p>
                <div class="border p-3 rounded bg-gray-50">
                    {!! nl2br(e($microData->deskripsi)) !!}
                </div>

                <p class="mt-4"><span class="font-semibold">Kategori:</span> {{ $microData->kategori->nama ?? '-' }}</p>
            </div>

            @if ($microData->gambar)
                <div>
                    <p class="font-semibold mb-2">Gambar:</p>
                    <img src="{{ asset('storage/' . $microData->gambar) }}" alt="Gambar"
                        class="rounded shadow w-full max-h-60 object-cover">
                </div>
            @endif
        </div>

        <div class="flex justify-between items-center mb-4 mt-10">
            <h2 class="text-lg font-semibold">Daftar Dataset</h2>
            <a href="{{ route('data-mikro.dataset.create', $microData->id) }}"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Tambah Dataset
            </a>
        </div>

        @if ($microData->items->isEmpty())
            <p class="text-gray-600">Belum ada dataset untuk data mikro ini.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border border-gray-300">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2">Judul Dataset</th>
                            <th class="px-4 py-2">Level Penyajian</th>
                            <th class="px-4 py-2">Harga</th>
                            <th class="px-4 py-2">Ukuran File</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($microData->items as $item)
                            <tr>
                                <td class="px-4 py-2">{{ $item->judul }}</td>
                                <td class="px-4 py-2">{{ $item->level_penyajian }}</td>
                                <td class="px-4 py-2">
                                    {{ $item->harga ? 'Rp' . number_format($item->harga, 0, ',', '.') : '-' }}</td>
                                <td class="px-4 py-2">{{ $item->ukuran_file }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <a href="{{ route('data-mikro.dataset.edit', [$microData->id, $item->id]) }}"
                                        class="text-blue-600 hover:underline">Edit</a>

                                    <form action="{{ route('data-mikro.dataset.destroy', [$microData->id, $item->id]) }}"
                                        method="POST" class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus dataset ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
