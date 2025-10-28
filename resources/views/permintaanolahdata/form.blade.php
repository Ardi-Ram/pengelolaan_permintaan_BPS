@extends('layouts.app')
@section('title', 'Forms Permintaan Data')

@section('content')
    <div class="bg-white m-5 rounded-lg border border-gray-300">
        <div class="flex-1 ">
            <div class="flex items-center justify-between border-b border-gray-200 p-4 ">
                <div class="flex items-center">
                    <span class="material-symbols-outlined mr-2 text-[28px] text-blue-600">assignment_add</span>
                    <h3 class="text-2xl font-bold">Form Permintaan Olah Data</h3>
                </div>
            </div>

            <div class="bg-white  rounded-lg  p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <strong>Terjadi kesalahan:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('permintaanolahdata.store') }}" method="POST">
                    @csrf
                    {{-- Informasi Pemilik Data --}}


                    <div class="shadow-sm mt-6 rounded-2xl border border-gray-300 overflow-hidden">
                        <h2 class="text-lg font-semibold text-gray-700 bg-gray-50 border-b border-gray-200 px-6 py-4">
                            <span
                                class="material-symbols-outlined text-gray-600 mr-2 text-[20px] align-middle">person</span>
                            Informasi Pemilik Data
                        </h2>

                        <div class="grid grid-cols-6 gap-6 bg-white p-6">
                            {{-- Nama --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="inline-flex items-center">
                                        <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1">badge</span>
                                        Nama
                                    </span>
                                </label>
                                <input type="text" name="nama" id="nama" required
                                    class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200 focus:border-gray-400 transition">
                            </div>

                            {{-- Instansi --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="instansi" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="inline-flex items-center">
                                        <span
                                            class="material-symbols-outlined text-gray-600 text-[18px] mr-1">business</span>
                                        Instansi
                                    </span>
                                </label>
                                <input type="text" name="instansi" id="instansi" required
                                    class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200 focus:border-gray-400 transition">
                            </div>

                            {{-- Email --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="inline-flex items-center">
                                        <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1">mail</span>
                                        Email
                                    </span>
                                </label>
                                <input type="email" name="email" id="email" required
                                    class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200 focus:border-gray-400 transition">
                            </div>

                            {{-- Nomor WA --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="inline-flex items-center">
                                        <span
                                            class="material-symbols-outlined text-gray-600 text-[18px] mr-1">phone_iphone</span>
                                        Nomor WA
                                    </span>
                                </label>
                                <input type="text" name="no_wa" id="no_wa" required
                                    class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200 focus:border-gray-400 transition">
                            </div>

                            {{-- Perantara --}}
                            <div class="col-span-6 sm:col-span-3">
                                <label for="perantara_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    <span class="inline-flex items-center">
                                        <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1">hub</span>
                                        Perantara Permintaan
                                    </span>
                                </label>
                                <select name="perantara_id" id="perantara_id" required
                                    class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200 focus:border-gray-400 transition">
                                    <option value="">-- Pilih Perantara --</option>
                                    @foreach ($perantaraData as $p)
                                        <option value="{{ $p->id }}">{{ $p->nama_perantara }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>


                    {{-- Jumlah Data --}}
                    <div class="mt-8">
                        <label for="jumlah_data" class="block text-sm font-medium text-gray-700 mb-1">Jumlah Data</label>
                        <div class="flex items-center gap-3">
                            <input type="number" name="jumlah_data" id="jumlah_data" min="1"
                                class="rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring focus:ring-cyan-100 w-32">
                            <button type="button" id="generate-data"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition  border border-gray-400">Buat
                                Form Data</button>
                        </div>
                    </div>

                    {{-- Input Dinamis --}}
                    <div id="data-inputs" class="mt-8 space-y-8"></div>

                    {{-- Tombol Submit --}}
                    <div class="mt-10">
                        <button type="submit"
                            class="px-6 py-2 rounded-lg bg-cyan-100 text-cyan-900 hover:bg-cyan-200 transition font-medium  border border-cyan-800">
                            Simpan Permintaan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('components.script.form-permintaan')
@endsection
