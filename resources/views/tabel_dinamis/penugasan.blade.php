@extends('layouts.app')

@section('title', 'Penugasan Tabel Statistik')

@section('content')
    <div class="bg-white m-5 rounded-lg border border-gray-300">
        <div class="flex-1">
            <h1 class="text-2xl font-bold flex items-center p-4 border-b border-gray-300">
                <span class="material-symbols-outlined mr-2 text-[28px]">assignment_ind</span>
                Penugasan Tabel Statistik
            </h1>

            <div class="max-w-7xl mx-auto px-5 py-5">
                {{-- Filter --}}
                <div class="flex flex-wrap items-center justify-between mb-4 gap-4">
                    <div class="flex items-center gap-2">
                    </div>

                    <div id="custom-controls" class="flex items-center gap-4"></div>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table id="tabel-dinamis-table"
                        class="min-w-full border-separate border-spacing-y-2 text-sm text-left text-gray-700">
                        <thead class=" text-gray-600 font-semibold text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Judul</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Tanggal Rilis</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            {{-- Modal Penugasan --}}
            <div id="modal-penugasan"
                class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white w-full max-w-md rounded-lg p-6 shadow-lg border border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Penugasan Tabel Dinamis</h2>
                    <form id="form-penugasan" method="POST">
                        @csrf
                        <input type="hidden" name="tabel_id" id="tabel_id">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pengolah</label>
                            <select name="pengolah_id" id="pengolah_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring focus:border-blue-400"
                                required>
                                <option value="">-- Pilih Pengolah --</option>
                                @foreach ($pengolahList as $pengolah)
                                    <option value="{{ $pengolah->id }}">{{ $pengolah->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end gap-2 mt-6">
                            <button type="button" id="close-modal"
                                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL ALASAN -->
            <div id="modal-alasan" tabindex="-1" aria-hidden="true"
                class="hidden fixed inset-0 z-50 overflow-y-auto overflow-x-hidden flex justify-center items-center bg-black/50">
                <div class="relative p-4 w-full max-w-2xl max-h-full">
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">

                        <div
                            class="flex items-center justify-between p-4 border-b rounded-t border-gray-200 dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Alasan Penolakan Penugasan
                            </h3>
                            <button type="button"
                                class="text-gray-400 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                id="btn-close-modal">
                                <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                                </svg>
                                <span class="sr-only">Tutup modal</span>
                            </button>
                        </div>

                        <div class="p-4 md:p-6 space-y-4">
                            <p class="text-base text-gray-500 dark:text-gray-300">
                                <span class="font-semibold text-gray-700 dark:text-white">Dari:</span>
                                <span id="modal-nama-pengolah">-</span>
                            </p>
                            <p class="text-base leading-relaxed text-gray-600 dark:text-gray-300 whitespace-pre-wrap"
                                id="modal-alasan-text">
                            </p>
                        </div>

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
    @include('components.script.penugasan-tabel-statistik')
@endsection
