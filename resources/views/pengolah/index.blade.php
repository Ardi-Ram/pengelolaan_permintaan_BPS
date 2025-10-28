@extends('layouts.app')

@section('title', 'Dftar Permintaan Data')

@section('content')
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
        <div class="bg-white rounded-lg border border-gray-300 w-full">
            <div class="flex-1">
                <h1 class="text-2xl font-bold flex items-center p-4 border-b border-gray-300">
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 mr-2 text-blue-600">
                        <span class="material-symbols-outlined  text-[24px] text-blue-600">folder</span>
                    </span>
                    Daftar Permintaan Data
                </h1>

                <div class="max-w-7xl mx-auto px-5 py-5">
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('error') && session('openUploadId'))
                        <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md text-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Filter dan search sejajar --}}
                    <div class="flex flex-wrap items-center justify-between mb-4 gap-4">
                        <div class="flex items-center gap-2">
                            <select id="filter-kategori"
                                class="w-40 border border-gray-300 rounded px-3 py-2 text-sm focus:ring focus:border-blue-400">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="custom-controls" class="flex items-center gap-4">
                            {{-- DataTables length and filter will be moved here by JS --}}
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table id="permintaan-table"
                            class="min-w-full border-separate border-spacing-y-2 text-sm text-left text-gray-700">
                            <thead class=" text-gray-600 font-semibold text-xs uppercase ">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="px-4 py-3">Kode Transaksi</th>
                                    <th class="px-4 py-3">Judul</th>
                                    <th class="px-4 py-3">Kategori</th>
                                    <th class="px-4 py-3">Petugas PST</th>
                                    <th class="px-4 py-3">Tanggal Dibuat</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- DataTables will populate this tbody --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Modal Reject --}}
                <div id="rejectModal"
                    class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 z-50 flex justify-center items-center">
                    <div class="bg-white p-6 rounded-lg w-96 shadow-xl border border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Alasan Penolakan</h2>
                        <form id="rejectForm" method="POST">
                            @csrf
                            <textarea name="alasan" required
                                class="w-full h-24 border border-gray-300 rounded-md p-2 text-sm mb-4 focus:outline-none focus:ring focus:border-blue-400"
                                placeholder="Tuliskan alasan penolakan..."></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="closeRejectModal()"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- Modal Deskripsi -->
                <!-- Modal Deskripsi -->
                <div id="deskripsiModal"
                    class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex justify-center items-center">
                    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl border border-gray-200 relative">
                        <!-- Header -->
                        <div
                            class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-100 rounded-t-2xl">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600 text-xl">info</span>
                                <h2 class="text-lg font-semibold text-gray-700">Deskripsi Permintaan</h2>
                            </div>
                            <button onclick="closeDeskripsiModal()"
                                class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-5 bg-white max-h-[400px] overflow-y-auto">
                            <div id="deskripsiContent"
                                class="text-gray-700 text-sm leading-relaxed whitespace-pre-line border border-dashed border-gray-300 p-4 bg-gray-50 rounded-lg shadow-inner min-h-[150px]">
                                Memuat deskripsi...
                            </div>
                        </div>

                        <!-- Footer (Optional) -->
                        <div class="px-6 py-4 border-t border-gray-200 flex justify-end bg-gray-50 rounded-b-2xl">
                            <button onclick="closeDeskripsiModal()"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-medium">Tutup</button>
                        </div>
                    </div>
                </div>
                <!-- Modal Upload -->
                <!-- Modal Upload -->
                <div id="uploadModal"
                    class="fixed inset-0 hidden bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center px-4">
                    <div
                        class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 relative border border-gray-100 animate-fade-in">

                        <!-- Header -->
                        <h2 class="text-xl font-semibold text-gray-800 mb-5 flex items-center gap-2 border-b pb-3">
                            <span class="material-symbols-outlined text-blue-500 text-3xl">cloud_upload</span>
                            Upload Hasil Olahan
                        </h2>

                        <!-- Judul Permintaan -->
                        <div class="mb-4 text-sm text-gray-700 bg-gray-50 border border-gray-200 rounded-md px-4 py-2">
                            <span class="font-semibold">Judul Permintaan:</span>
                            <span id="judulPermintaan" class="ml-2 italic text-gray-500">-</span>
                        </div>

                        <!-- Aturan Upload -->
                        <div class="mb-5 p-4 text-sm bg-blue-50 border-l-4 border-blue-400 text-blue-900 rounded-md">
                            <p class="font-semibold mb-1">📘 Ketentuan Upload:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Ekstensi file: <span class="font-semibold text-blue-800">.xls, .xlsx, .csv</span></li>
                                <li>Data harus telah diverifikasi sebelum diunggah</li>
                                <li>Maksimum ukuran file: <span class="font-semibold text-blue-800">10 MB</span></li>
                            </ul>
                        </div>

                        <!-- Form Upload -->
                        <form id="uploadForm" method="POST" action="" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            <label class="block">
                                <span class="text-gray-700 font-medium text-sm">Pilih File:</span>
                                <input type="file" name="file_hasil" id="file_hasil" accept=".xls,.xlsx,.csv"
                                    class="mt-2 block w-full text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500
                    file:bg-blue-600 file:text-white file:font-medium file:px-4 file:py-2 file:rounded-md file:border-none 
                    file:cursor-pointer file:hover:bg-blue-700 transition duration-150 ease-in-out"
                                    required>
                                <div id="uploadError" class="text-sm text-red-600 mt-1 hidden"></div>
                            </label>

                            <!-- Tombol -->
                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" onclick="closeUploadModal()"
                                    class="px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm transition">
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


                <div id="alasanModal"
                    class="hidden fixed inset-0 bg-black bg-opacity-40 z-50 flex justify-center items-center">
                    <div class="bg-white rounded-2xl w-full max-w-2xl shadow-2xl border border-gray-200 relative">
                        <!-- Header -->
                        <div
                            class="flex items-center justify-between px-6 py-4 border-b border-gray-200 bg-gray-100 rounded-t-2xl">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-red-600 text-xl">report</span>
                                <h2 class="text-lg font-semibold text-gray-700">Alasan Verifikasi Ditolak</h2>
                            </div>
                            <button onclick="closeAlasanModal()"
                                class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
                        </div>

                        <!-- Body -->
                        <div class="px-6 py-5 bg-white max-h-[400px] overflow-y-auto">
                            <div id="alasanContent"
                                class="text-gray-700 text-sm leading-relaxed whitespace-pre-line border border-dashed border-red-300 p-4 bg-red-50 rounded-lg shadow-inner min-h-[150px]">
                                Memuat alasan...
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="px-6 py-4 border-t border-gray-200 flex justify-end bg-gray-50 rounded-b-2xl">
                            <button onclick="closeAlasanModal()"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-medium">Tutup</button>
                        </div>
                    </div>
                </div>





                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        @if (session('error') && session('openUploadId'))
                            openUploadModal({{ session('openUploadId') }});
                        @endif
                    });
                </script>

                <script>
                    function openDeskripsiModal(deskripsi) {
                        document.getElementById('deskripsiContent').textContent = deskripsi;
                        document.getElementById('deskripsiModal').classList.remove('hidden');
                    }

                    function closeDeskripsiModal() {
                        document.getElementById('deskripsiModal').classList.add('hidden');
                    }
                </script>
                <script>
                    function openAlasanModal(alasan) {
                        document.getElementById('alasanContent').textContent = alasan || 'Tidak ada alasan yang diberikan.';
                        document.getElementById('alasanModal').classList.remove('hidden');
                    }

                    function closeAlasanModal() {
                        document.getElementById('alasanModal').classList.add('hidden');
                    }
                </script>


                <script>
                    function openUploadModal(id) {
                        const form = document.getElementById('uploadForm');
                        form.action = `/pengolah/upload/${id}`;
                        document.getElementById('uploadModal').classList.remove('hidden');

                        // Reset pesan error saat buka modal
                        document.getElementById('uploadError').classList.add('hidden');
                        document.getElementById('uploadError').textContent = '';
                    }

                    function closeUploadModal() {
                        document.getElementById('uploadModal').classList.add('hidden');
                        document.getElementById('uploadForm').reset();
                        document.getElementById('uploadError').classList.add('hidden');
                        document.getElementById('uploadError').textContent = '';
                    }

                    // Validasi sebelum submit form
                    document.getElementById('uploadForm').addEventListener('submit', function(e) {
                        const fileInput = document.getElementById('file_hasil');
                        const errorDiv = document.getElementById('uploadError');
                        const file = fileInput.files[0];
                        const allowedExtensions = ['xls', 'xlsx', 'csv'];
                        const maxSize = 10 * 1024 * 1024; // 10 MB

                        // Reset error
                        errorDiv.textContent = '';
                        errorDiv.classList.add('hidden');

                        if (!file) {
                            e.preventDefault();
                            errorDiv.textContent = 'Silakan pilih file terlebih dahulu.';
                            errorDiv.classList.remove('hidden');
                            return;
                        }

                        const extension = file.name.split('.').pop().toLowerCase();
                        if (!allowedExtensions.includes(extension)) {
                            e.preventDefault();
                            errorDiv.textContent = 'Format file tidak valid. Hanya .xls, .xlsx, atau .csv.';
                            errorDiv.classList.remove('hidden');
                            return;
                        }

                        if (file.size > maxSize) {
                            e.preventDefault();
                            errorDiv.textContent = 'Ukuran file melebihi batas maksimum 10 MB.';
                            errorDiv.classList.remove('hidden');
                            return;
                        }
                    });
                </script>



                <script>
                    function openRejectModal(id) {
                        const form = document.getElementById('rejectForm');
                        form.action = "{{ url('/pengolah/reject') }}/" + id;
                        document.getElementById('rejectModal').classList.remove('hidden');
                    }

                    function closeRejectModal() {
                        document.getElementById('rejectModal').classList.add('hidden');
                    }
                </script>

                <script>
                    $(document).ready(function() {
                        const table = $('#permintaan-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: "{{ route('pengolah.permintaan.data') }}",
                                type: "GET",
                                data: function(d) {
                                    d.kategori = $('#filter-kategori').val(); // kirim kategori ke backend
                                }
                            },
                            columns: [{
                                    data: 'DT_RowIndex',
                                    className: 'text-gray-400 text-sm',
                                    orderable: false,
                                    searchable: false
                                },
                                {
                                    data: 'kode_transaksi',
                                    name: 'kode_transaksi'
                                },
                                {
                                    data: 'judul_permintaan',
                                    name: 'judul_permintaan'
                                },
                                {
                                    data: 'kategori',
                                    name: 'kategori'
                                },
                                {
                                    data: 'petugas_pst',
                                    name: 'petugas_pst'
                                },
                                {
                                    data: 'created_at',
                                    name: 'created_at'
                                }, {
                                    data: 'status',
                                    name: 'status'
                                },
                                {
                                    data: 'aksi',
                                    orderable: false,
                                    searchable: false
                                }
                            ],
                            createdRow: function(row, data, dataIndex) {
                                $(row).addClass('bg-white shadow-sm rounded-md'); // Apply consistent row style
                            },
                            dom: '<"hidden"l><"hidden"f>t<"flex justify-between items-center mt-4"ip>', // hide bawaan filter dan lengthmenu
                            initComplete: function() {
                                // Move and style length menu
                                $('#custom-controls').prepend($('.dataTables_length select').addClass(
                                    'border border-gray-300 rounded-md px-6 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                                ));

                                // Move and style search box
                                $('#custom-controls').append($('.dataTables_filter input').addClass(
                                    'border border-gray-300 px-3 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                                ));

                                // Trigger reload saat kategori berubah
                                $('#filter-kategori').on('change', function() {
                                    table.ajax.reload();
                                });
                            },
                            language: {
                                search: "",
                                searchPlaceholder: "Cari permintaan...",
                                lengthMenu: "Tampilkan _MENU_ entri",
                                emptyTable: `
                                    <div class="text-center py-8">
                                        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">inbox</span>
                                        <p class="text-gray-600 text-lg font-semibold">Belum ada permintaan data untuk ditampilkan.</p>
                                        <p class="text-gray-500 text-sm mt-1">Coba sesuaikan filter atau tunggu penugasan baru.</p>
                                    </div>
                                `,
                                paginate: {
                                    previous: "Sebelumnya",
                                    next: "Berikutnya"
                                }
                            },
                            drawCallback: function() {
                                $('.dataTables_paginate').addClass('mt-4 flex justify-center gap-1');
                                $('.dataTables_paginate a').addClass(
                                    'px-3 py-1 rounded border text-sm text-blue-600 hover:bg-blue-100 transition'
                                );
                                $('.dataTables_paginate .current').addClass(
                                    'bg-blue-500 text-white border-blue-500');
                            }
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
