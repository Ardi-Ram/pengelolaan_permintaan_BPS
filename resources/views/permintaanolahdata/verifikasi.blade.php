@extends('layouts.petugas')

@section('content')
    <div class="max-w-7xl mx-auto my-8 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-white text-3xl">task_alt</span>
                    <h2 class="text-2xl font-bold text-white">Verifikasi Hasil Olahan</h2>
                </div>
            </div>

            {{-- Content --}}
            <div class="p-6 space-y-6">
                {{-- Informasi Permintaan --}}
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                    <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-blue-600">info</span>
                        Informasi Permintaan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-blue-500 text-lg mt-0.5">description</span>
                            <div>
                                <p class="text-blue-700 font-medium">Judul Permintaan</p>
                                <p class="text-blue-600">{{ $hasil->permintaanData->judul_permintaan }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <span class="material-symbols-outlined text-blue-500 text-lg mt-0.5">tag</span>
                            <div>
                                <p class="text-blue-700 font-medium">Kode Transaksi</p>
                                <p class="text-blue-600">{{ $hasil->permintaanData->pemilikData->kode_transaksi ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Main Content Grid --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                    {{-- File Download Section --}}
                    <div class="xl:col-span-2 space-y-4">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="material-symbols-outlined text-green-600 text-2xl">folder_open</span>
                            <h3 class="text-xl font-semibold text-gray-800">File Hasil Olahan</h3>
                        </div>

                        <div
                            class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex items-center gap-3 min-w-0 flex-1">
                                    <div class="flex-shrink-0">
                                        <span class="material-symbols-outlined text-gray-500 text-2xl">description</span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-700 truncate">{{ $hasil->nama_file }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Klik download untuk melihat file</p>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ $fileUrl }}" target="_blank"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors">
                                        <span class="material-symbols-outlined text-base">download</span>
                                        Download File
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Verifikasi Section --}}
                    {{-- ...potongan awal tidak berubah --}}

                    {{-- Form Verifikasi Section --}}
                    <div class="xl:col-span-1">
                        <div class="bg-gray-50 rounded-lg border border-gray-200 p-5 sticky top-6">
                            <div class="flex items-center gap-2 mb-4">
                                <span class="material-symbols-outlined text-indigo-600 text-2xl">verified</span>
                                <h3 class="text-lg font-semibold text-gray-800">Form Verifikasi</h3>
                            </div>

                            <form method="POST" action="{{ route('verifikasi.store', $hasil->id) }}" id="verifikasiForm"
                                class="space-y-4">
                                @csrf

                                {{-- Dropdown Status --}}
                                <div>
                                    <label for="verifikasi_hasil" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status Verifikasi <span class="text-red-500">*</span>
                                    </label>
                                    <select name="verifikasi_hasil" id="verifikasi_hasil" required
                                        class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Pilih Status Verifikasi</option>
                                        <option value="valid">✅ Valid</option>
                                        <option value="tidak_valid">❌ Tidak Valid</option>
                                    </select>
                                </div>

                                {{-- Alasan jika tidak valid --}}
                                <div id="alasanContainer" class="hidden">
                                    <label for="alasan" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alasan Penolakan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="catatan_verifikasi" id="alasan" rows="4"
                                        class="w-full border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                        placeholder="Jelaskan secara detail mengapa data tidak valid..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Berikan penjelasan yang jelas dan konstruktif</p>
                                </div>

                                {{-- Tombol Aksi --}}
                                <div class="flex flex-col sm:flex-row gap-2 pt-4 border-t border-gray-200">
                                    <a href="{{ route('permintaanolahdata.status') }}"
                                        class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors text-center">
                                        Batal
                                    </a>
                                    <button type="submit"
                                        class="flex-1 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                        Simpan Verifikasi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Google Icon Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />

    {{-- Script Toggle Alasan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.getElementById('verifikasi_hasil');
            const alasanContainer = document.getElementById('alasanContainer');
            const alasanTextarea = document.getElementById('alasan');
            const form = document.getElementById('verifikasiForm');

            function toggleAlasan() {
                const isNotValid = dropdown.value === 'tidak_valid';

                if (isNotValid) {
                    alasanContainer.classList.remove('hidden');
                    alasanTextarea.setAttribute('required', 'required');
                } else {
                    alasanContainer.classList.add('hidden');
                    alasanTextarea.removeAttribute('required');
                    alasanTextarea.value = '';
                }
            }

            dropdown.addEventListener('change', toggleAlasan);
            toggleAlasan(); // Initialize on page load

            form.addEventListener('submit', function(e) {
                if (dropdown.value === 'tidak_valid' && !alasanTextarea.value.trim()) {
                    e.preventDefault();
                    alert('Alasan penolakan harus diisi jika data tidak valid.');
                    alasanTextarea.focus();
                    return false;
                }
            });
        });
    </script>

    {{-- Custom Styles --}}
    <style>
        .sticky {
            position: -webkit-sticky;
            position: sticky;
        }

        @media (max-width: 1279px) {
            .sticky {
                position: static;
            }
        }
    </style>
@endsection
