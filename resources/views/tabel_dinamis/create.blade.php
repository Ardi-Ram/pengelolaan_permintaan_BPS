@extends('layouts.petugas')

@section('content')
    <div class="mx-6 my-6">
        <div class="shadow-sm rounded-2xl border border-gray-200 overflow-hidden bg-white">
            <h2 class="text-lg font-semibold text-gray-700 bg-gray-50 border-b border-gray-200 px-6 py-4">
                <span class="material-symbols-outlined text-gray-600 mr-2 align-middle text-[20px]">table_view</span>
                Form Permintaan Tabel Statistik
            </h2>
            @if (session('success'))
                <script>
                    $(document).ready(function() {
                        toastr.success("{{ session('success') }}");
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    $(document).ready(function() {
                        toastr.error("Terdapat kesalahan pada input data.");
                    });
                </script>
            @endif


            <form action="{{ route('tabel-dinamis.store') }}" method="POST" class="px-6 py-4">
                @csrf

                <div id="form-wrapper" class="space-y-6">
                    <div class="border border-gray-200 p-6 rounded-2xl bg-gray-50" id="form-item-0">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span
                                    class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">title</span>
                                Judul Tabel
                            </label>
                            <input type="text" name="data[0][judul]"
                                class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span
                                    class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">description</span>
                                Deskripsi
                            </label>
                            <textarea name="data[0][deskripsi]" rows="3"
                                class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200" required></textarea>
                        </div>

                        <!-- Select Kategori -->
                        <!-- Select Kategori -->
                        <div class="mb-4">
                            <label for="kategori-0" class="block text-sm font-medium text-gray-700 mb-1">
                                <span
                                    class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">category</span>
                                Kategori
                            </label>
                            <select name="data[0][kategori_id]" id="kategori-0"
                                class="kategori-dropdown w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200"
                                data-index="0" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Select Subject -->
                        <div class="mb-4">
                            <label for="subject-0" class="block text-sm font-medium text-gray-700 mb-1">
                                <span
                                    class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">badge</span>
                                Subject
                            </label>
                            <select name="data[0][subject_id]" id="subject-0"
                                class="subject-dropdown w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200"
                                required>
                                <option value="">-- Pilih Subject --</option>
                                {{-- Subject akan diisi lewat JS berdasarkan kategori --}}
                            </select>
                        </div>



                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span
                                    class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">calendar_today</span>
                                Tanggal Rilis
                            </label>
                            <input type="date" name="data[0][deadline]"
                                class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200">
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="button" id="add-form"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 rounded-lg border border-green-300 hover:bg-green-200 transition text-sm">
                        <span class="material-symbols-outlined text-[18px]">add</span> Tambah Tabel Lagi
                    </button>
                </div>

                <div class="mt-8">
                    <button type="submit"
                        class="px-6 py-2 bg-gray-700 text-white rounded-lg border border-gray-800 hover:bg-gray-800 transition text-sm font-medium">
                        Simpan Permintaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    @include('components.script.form-tabel-statistik')
@endsection
