@extends('layouts.petugas')

@section('title', 'Verifikasi Hasil')

@section('content')
    <div class="max-w-4xl mx-auto mt-12">
        <div class="bg-white border border-gray-200 rounded-xl shadow-md p-8">
            <div class="flex items-center gap-2 mb-6">
                <span class="material-symbols-outlined text-blue-600 text-3xl">task_alt</span>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Verifikasi Hasil Tabel:
                    <span class="text-blue-700">{{ $tabel->judul }}</span>
                </h2>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-medium mb-1 text-sm flex items-center gap-1">
                    <span class="material-symbols-outlined text-blue-500 text-base">link</span>
                    Link Hasil:
                </label>
                <a href="{{ $tabel->link_hasil }}" target="_blank"
                    class="inline-block text-blue-600 underline break-all hover:text-blue-800 text-sm transition">
                    {{ $tabel->link_hasil }}
                </a>
            </div>

            <form action="{{ route('tabel-dinamis.verifikasi.simpan', $tabel->id) }}" method="POST"
                class="space-y-6 text-base">
                @csrf

                <div>
                    <label for="verifikasi-select" class="block font-medium text-gray-700 mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-blue-500 text-base">check_circle</span>
                        Status Verifikasi
                    </label>
                    <select name="verifikasi_pst" id="verifikasi-select" required
                        class="border border-gray-300 rounded-md w-full px-4 py-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Pilih Status --</option>
                        <option value="1">Valid</option>
                        <option value="0">Tidak Valid</option>
                    </select>
                </div>

                <div id="catatan-field" class="hidden">
                    <label for="catatan" class="block font-medium text-gray-700 mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-red-500 text-base">edit_note</span>
                        Catatan (jika Tidak Valid)
                    </label>
                    <textarea name="catatan_verifikasi" id="catatan" rows="4"
                        class="border border-gray-300 rounded-md w-full px-4 py-2 focus:ring-red-500 focus:border-red-500 text-sm"
                        placeholder="Tuliskan alasan atau perbaikan yang perlu dilakukan oleh Pengolah..."></textarea>
                    @error('catatan_verifikasi')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2.5 rounded-lg transition">
                        <span class="material-symbols-outlined align-middle text-sm mr-1">save</span>
                        Simpan Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script untuk toggle dan validasi --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('verifikasi-select');
            const catatanField = document.getElementById('catatan-field');
            const form = document.querySelector('form');

            function toggleCatatanField() {
                if (select.value === '0') {
                    catatanField.classList.remove('hidden');
                } else {
                    catatanField.classList.add('hidden');
                }
            }

            select.addEventListener('change', toggleCatatanField);
            toggleCatatanField();

            form.addEventListener('submit', function(e) {
                if (select.value === '0') {
                    const catatanInput = form.querySelector('textarea[name="catatan_verifikasi"]');
                    if (!catatanInput.value.trim()) {
                        e.preventDefault();
                        alert('Harap isi catatan verifikasi jika status Tidak Valid.');
                        catatanInput.focus();
                    }
                }
            });
        });
    </script>
@endsection
