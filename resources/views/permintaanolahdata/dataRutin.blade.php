@extends('layouts.admin')

@section('content')
    <div class="max-w-full  m-5 bg-white rounded-xl shadow">
        <h2 class="text-xl font-semibold mb-4 border-b-2 border-gray-100 p-6">Form Permintaan Data Rutin</h2>


        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('permintaan_data_rutin.store') }}" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label class="block font-medium mb-1">Judul Data</label>
                        <input type="text" name="judul" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium mb-1">Kategori Data</label>
                        <select name="kategori_id" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium ">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">{{ old('deskripsi') }}</textarea>
                    </div>
                </div>
                <div>
                    <label class="block font-medium mb-2">Pilih Pengolah</label>
                    <div class="space-y-3 max-h-[300px] overflow-y-auto border p-3 rounded">
                        @foreach ($pengolahs as $pengolah)
                            <label
                                class="flex items-center gap-3 border border-gray-200 p-2 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="pengolah_id" value="{{ $pengolah->id }}" required
                                    {{ old('pengolah_id') == $pengolah->id ? 'checked' : '' }}>
                                <div>
                                    <div class="font-semibold">{{ $pengolah->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $pengolah->email }}</div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Simpan Permintaan
                </button>
            </div>
        </form>

    </div>
@endsection
