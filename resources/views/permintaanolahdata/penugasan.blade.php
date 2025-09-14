@extends('layouts.petugas')

@section('content')
    <div class="bg-white m-5 rounded-lg border border-gray-300">
        <div class="flex-1">
            <h1 class="text-2xl font-bold flex items-center gap-3 p-4 border-b border-gray-300">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600">
                    <span class="material-symbols-outlined text-[24px]">person_add</span>
                </span>
                Penugasan Data
            </h1>


            <div class="max-w-7xl mx-auto px-5 py-5">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Filter dan search sejajar --}}

                <div class="overflow-x-auto">
                    <table id="permintaan-table"
                        class="min-w-full border-separate border-spacing-y-2 text-sm text-left text-gray-700">
                        <thead class="text-gray-600 font-semibold text-xs uppercase rounded-md">
                            <tr>
                                <th class="px-4 py-2">No</th>
                                <th class="px-4 py-2">Judul Permintaan</th>
                                <th class="px-4 py-2">Kategori</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Tanggal Dibuat</th>
                                <th class="px-4 py-2">Kode</th>
                                <th class="px-4 py-2 text-left flex items-center gap-1">
                                    Aksi
                                    <button id="btn-info-aksi" class="text-gray-500 hover:text-gray-700">
                                        <span class="material-icons-outlined text-sm">info</span>
                                    </button>
                                </th>
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
                                        <option value="draf">Draf</option>
                                        <option value="ditolak">Ditolak</option>
                                    </select>
                                </th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>


                    </table>
                </div>
            </div>

            <div id="modal-info-aksi"
                class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-30">
                <div class="bg-white w-full max-w-sm rounded-lg p-5 shadow-xl border border-gray-200">
                    <h2 class="text-base font-semibold text-gray-800 mb-3 flex items-center gap-2">
                        <span class="material-icons-outlined text-blue-500">info</span>
                        Penjelasan Tombol Aksi
                    </h2>
                    <ul class="space-y-3 text-sm text-gray-700">
                        <li class="flex items-start gap-3">
                            <span class="material-icons-outlined text-yellow-500 text-base">edit</span>
                            <div>
                                <span class="font-medium">Edit permintaan</span><br>
                                Mengubah data detail permintaan sebelum diproses lebih lanjut.
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-icons-outlined text-blue-500 text-base">person_add</span>
                            <div>
                                <span class="font-medium">Tugaskan pengolah</span><br>
                                Menetapkan staf/pengolah yang akan menangani permintaan data.
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="material-icons-outlined text-red-500 text-base">info</span>
                            <div>
                                <span class="font-medium">Lihat alasan penolakan</span><br>
                                Melihat keterangan/alasan mengapa permintaan data ditolak.
                            </div>
                        </li>
                    </ul>
                    <div class="flex justify-end mt-5">
                        <button id="close-info-aksi"
                            class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>


            {{-- Modal Penugasan --}}
            <div id="modal-penugasan"
                class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-40">
                <div class="bg-white w-full max-w-md rounded shadow p-6 border border-gray-200">
                    <h2 class="text-base font-semibold text-gray-800 mb-4 border-b pb-2">Penugasan Permintaan</h2>
                    <form id="form-penugasan" method="POST" action="{{ route('permintaan.penugasan') }}">
                        @csrf
                        <input type="hidden" name="permintaan_id" id="permintaan_id">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pengolah</label>
                            <select name="pengolah_id"
                                class="select2 w-full border border-gray-300 px-3 py-2 focus:outline-none focus:ring focus:border-blue-400">
                                @foreach ($pengolahList as $pengolah)
                                    <option value="{{ $pengolah->id }}">{{ $pengolah->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end gap-2 mt-4">
                            <button type="button" id="close-modal"
                                class="px-3 py-1.5 bg-gray-100 text-gray-700 hover:bg-gray-200 transition text-sm rounded">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 transition text-sm rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal Alasan Penolakan -->
            <div id="modal-alasan" tabindex="-1" aria-hidden="true"
                class="hidden fixed inset-0 z-50 overflow-y-auto overflow-x-hidden flex justify-center items-center bg-black/50">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                        <!-- Modal header -->
                        <div
                            class="flex items-center justify-between p-4 border-b rounded-t border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Alasan Penolakan Penugasan
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                id="btn-close-modal">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Tutup modal</span>
                            </button>
                        </div>

                        <!-- Modal body -->
                        <div class="p-4 md:p-6 space-y-4">
                            <p class="text-base text-gray-500 dark:text-gray-300">
                                <span class="font-semibold text-gray-700 dark:text-white">Dari:</span>
                                <span id="modal-nama-pengolah">-</span>
                            </p>
                            <p class="text-base leading-relaxed text-gray-600 dark:text-gray-300 whitespace-pre-wrap"
                                id="modal-alasan-text">
                            </p>
                        </div>

                        <!-- Modal footer -->
                        <div class="flex justify-end p-4 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                id="btn-close-modal-footer">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    @include('components.script.penugasan-permintaan')
@endsection
