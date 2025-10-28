@extends('layouts.app')

@section('title', 'Status Tabel Statistik')

@section('content')
    <div class="bg-white m-5 rounded-lg border border-gray-300">
        <div class="flex-1">
            <h1 class="text-2xl font-bold flex items-center gap-3 p-4 border-b border-gray-300">
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600">
                    <span class="material-symbols-outlined  text-[28px] text-blue-600">bar_chart</span>
                </span>
                Status Tabel Statistik
            </h1>
            <div class="max-w-7xl mx-auto px-5 py-5">
                <div class="overflow-x-auto">
                    <table id="status-tabel-dinamis"
                        class="min-w-full border-separate border-spacing-y-2 text-sm text-left text-gray-700">
                        <thead class=" text-gray-600 font-semibold text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">No</th>
                                <th class="px-4 py-3">Judul</th>
                                <th class="px-4 py-3">Kategori</th>
                                <th class="px-4 py-3">Tanggal Rilis</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Pengolah</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                            <!-- Row filter di bawah header -->
                            <tr class="bg-white">
                                <th>

                                </th>
                                <th>
                                    <div class="flex gap-4 ">
                                        <div id="custom-search"></div>
                                        <div id="custom-length"></div>
                                    </div>
                                </th>
                                <th>
                                    <select id="filter-kategori"
                                        class="w-32 border border-gray-300 rounded px-3 py-1 text-xs focus:ring focus:border-blue-400">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoriList as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </th>
                                <th></th>
                                <th>
                                    <select id="filter-status"
                                        class="w-24 border border-gray-300 rounded px-2 py-1 text-xs focus:ring focus:border-blue-400">
                                        <option value="">Semua</option>
                                        <option value="antrian">Antrian</option>
                                        <option value="proses">Proses</option>
                                        <option value="selesai">Selesai</option>
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

    {{-- Modal Publish Link --}}
    <div id="modal-publish" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded shadow max-w-md w-full">
            <h2 class="text-lg font-semibold mb-4">Masukkan Link Portal</h2>
            <form id="form-publish">
                @csrf
                <input type="url" name="link_publish" id="link_publish" required
                    placeholder="https://portal.bps.go.id/tabel/..."
                    class="w-full border border-gray-300 rounded px-3 py-2 mb-4">
                <input type="hidden" id="tabel_id">
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div id="editPublishModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg border shadow-md">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-600">edit</span>
                Edit Link Publikasi
            </h2>
            <form id="formEditLinkPublish">
                @csrf
                <input type="hidden" id="edit-id">
                <label class="block mb-1 text-sm font-medium text-gray-700">Link Publikasi Baru</label>
                <input type="url" id="edit-link-publish"
                    class="w-full border border-gray-300 rounded px-3 py-2 mb-4 focus:outline-none focus:ring focus:border-blue-400"
                    placeholder="https://..." required>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    @include('components.script.status-tabel-statistik')
@endsection
