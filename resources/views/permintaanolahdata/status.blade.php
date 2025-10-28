@extends('layouts.app')
@section('title', 'Status Permintaan Data')
@section('content')
    <div class="bg-white m-5 rounded-lg border border-gray-300">
        <div class="flex-1 ">
            <h1 class="text-2xl font-bold flex items-center p-4 border-b border-gray-300">
                <span class="material-symbols-outlined mr-2 text-[28px] text-blue-600"">monitoring</span>
                Status Permintaan Data
            </h1>
            <div class="max-w-7xl mx-auto py-3 px-3">

                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto  rounded-lg p-5">

                    <table id="status-table" class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700 ">
                        <thead class=" text-gray-600 font-semibold text-xs uppercase rounded-md">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Judul Permintaan</th>
                                <th class="px-4 py-2">Kategori</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Pengolah</th>
                                <th class="px-4 py-2">Kode</th>
                                <th class="px-4 py-2">Aksi</th>
                            </tr>
                            <tr class="bg-white">
                                <th></th>
                                <th>
                                    <div class="flex items-center gap-1">
                                        <div id="custom-length" class="min-w-[50px] text-xs"></div>
                                        <div id="custom-search" class="flex-1 text-xs"></div>
                                    </div>
                                </th>
                                <th>
                                    <select id="filter-kategori"
                                        class="w-32 border border-gray-300 rounded px-2 py-1 text-xs focus:ring focus:border-blue-400">
                                        <option value="">Semua</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <select id="filter-status"
                                        class="w-24 border border-gray-300 rounded px-2 py-1 text-xs focus:ring focus:border-blue-400">
                                        <option value="">Semua</option>
                                        <option value="antrian">Antrian</option>
                                        <option value="proses">Proses</option>
                                        <option value="selesai">Selesai</option>
                                    </select>
                                </th>
                                <th>
                                    <select id="filter-pengolah"
                                        class="w-28 border border-gray-300 rounded px-2 py-1 text-xs focus:ring focus:border-blue-400">
                                        <option value="">Semua</option>
                                        @foreach ($pengolahList as $pengolah)
                                            <option value="{{ $pengolah->id }}">{{ $pengolah->name }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th></th>
                                <th></th>
                            </tr>

                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('components.script.status-permintaan')
@endsection
