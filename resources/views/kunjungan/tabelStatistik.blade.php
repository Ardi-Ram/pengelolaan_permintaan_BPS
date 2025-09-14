@extends('layouts.tamu')

@section('content')
@section('title', 'Tabel Statistik | kunjungan')
<!-- Compact Header -->
<div class="bg-gray-100">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white/10 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-white text-2xl">analytics</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Portal Data Statistik</h1>
                        <p class="text-blue-100 text-sm">Akses data statistik dan publikasi</p>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button onclick="showModal('statistik')"
                        class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-1">
                        📊 Info Statistik
                    </button>
                    <button onclick="showModal('publikasi')"
                        class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-sm transition flex items-center gap-1">
                        📚 Info Publikasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6 m-2">
        <!-- Kiri (70%) -->
        <div class="w-full lg:w-[75%]">
            <!-- Compact Search Section -->
            <div class=" py-8">
                <div class="max-w-full mx-auto px-4">
                    <div class="bg-white rounded-xl shadow-md border overflow-hidden">
                        <div class="bg-blue-50 px-6 py-4 border-b">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-blue-600">search</span>
                                <h2 class="text-lg font-semibold text-gray-800">Pencarian Data</h2>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid md:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Tabel</label>
                                    <select id="jenis-tabel"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                        <option value="statistik">📊 Tabel Statistik</option>
                                        <option value="publikasi">📚 Tabel Publikasi</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kata Kunci</label>
                                    <input type="text" id="search-judul" placeholder="Masukkan kata kunci..."
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        onkeypress="if(event.key === 'Enter') searchTabel()" />
                                </div>
                                <div>
                                    <button onclick="searchTabel()"
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
                                        <span class="material-symbols-outlined text-sm">search</span>
                                        Cari Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Info Cards -->
                    <div class="grid md:grid-cols-2 gap-4 mt-6">
                        <div class="bg-white rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-blue-600 text-lg">update</span>
                                <h3 class="font-medium text-blue-800">Update Berkala</h3>
                            </div>
                            <p class="text-sm text-blue-700">Data diperbarui secara berkala untuk akurasi informasi.</p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-blue-100">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-blue-600 text-lg">search</span>
                                <h3 class="font-medium text-blue-800">Pencarian Spesifik</h3>
                            </div>
                            <p class="text-sm text-blue-700">Gunakan kata kunci untuk pencarian yang lebih tepat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="results-section" class="hidden p-4">
                <div class="max-w-full mx-auto px-4 sm:px-0">
                    <!-- Statistik Results -->
                    <div id="statistik-container" class="hidden">
                        <div class="bg-white rounded-lg  border p-3 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-white text-2xl">bar_chart</span>
                                    <h3 class="text-xl font-bold text-white">Hasil Pencarian Tabel Statistik</h3>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table id="statistik-table" class="w-full">
                                    <thead class="bg-blue-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                                <div class="flex items-center gap-2">
                                                    <span>Judul</span>
                                                    <div id="custom-length-statistik"></div>
                                                </div>
                                            </th>

                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Terakhir
                                                Diperbarui</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Publikasi Results -->
                    <div id="publikasi-container" class="hidden">
                        <div class="bg-white rounded-lg border p-4 overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <span class="material-symbols-outlined text-white text-2xl">library_books</span>
                                    <h3 class="text-xl font-bold text-white">Hasil Pencarian Tabel Publikasi</h3>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table id="publikasi-table" class="w-full">
                                    <thead class="bg-blue-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">No</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">
                                                <div class="flex items-center gap-2">
                                                    <span>Judul Tabel</span>
                                                    <div id="custom-length"></div>
                                                </div>
                                            </th>

                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Nomor
                                                Tabel
                                            </th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Judul
                                                Publikasi</th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Halaman
                                            </th>
                                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Unduh
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan (30%) -->
        <div class="w-full lg:w-[25%] space-y-6">
            <div class="text-blue-50 rounded-lg p-6">
                <div class="mt-6">
                    <div class="bg-white shadow rounded-lg p-4 space-y-4">
                        @foreach ($groups as $group)
                            <div>
                                <div
                                    class="text-center font-semibold text-blue-800 text-sm uppercase tracking-wide mb-2">
                                    {{ $group->name }}
                                </div>
                                <ul class="divide-y divide-gray-200">
                                    @foreach ($group->links->sortBy('order') as $link)
                                        <li>
                                            <a href="{{ $link->url }}" target="_blank"
                                                class="flex justify-between items-center py-1 text-sm text-blue-700 hover:underline">
                                                {{ $link->label }}
                                                <svg class="w-3 h-3 text-blue-500 ml-2" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>

                    <!-- Modal untuk banner -->
                    <div x-data="{ open: false, imageUrl: '' }">
                        <div x-show="open"
                            class="fixed inset-0 z-50 bg-black bg-opacity-70 flex items-center justify-center"
                            x-transition.opacity @click.self="open = false">
                            <div class="max-w-4xl mx-auto p-4">
                                <img :src="imageUrl" class="rounded shadow-lg max-h-[90vh] w-auto mx-auto">
                            </div>
                        </div>
                        @foreach ($banners as $banner)
                            <div class="mt-4 cursor-pointer"
                                @click="open = true; imageUrl = '{{ asset('storage/' . $banner->image_path) }}'">
                                <img src="{{ asset('storage/' . $banner->image_path) }}" alt="{{ $banner->title }}"
                                    class="rounded shadow w-full object-cover hover:opacity-90 transition">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let statistikTable = null;
        let publikasiTable = null;

        function searchTabel() {
            const keyword = $('#search-judul').val().trim();
            const jenis = $('#jenis-tabel').val();

            $('#statistik-container, #publikasi-container, #results-section').addClass('hidden');

            if (!keyword) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Silakan masukkan kata kunci pencarian!',
                    confirmButtonColor: '#2563eb'
                });
                return;
            }

            Swal.fire({
                title: 'Mencari data...',
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            if (jenis === 'statistik') {
                if (!statistikTable) {
                    statistikTable = $('#statistik-table').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        responsive: true,
                        pageLength: 25,
                        dom: "<'top-area' l>rt<'flex items-center justify-between px-6 py-2' ip>",
                        language: {
                            processing: '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><span class="ml-3">Memuat data...</span></div>',
                            lengthMenu: "Tampilkan _MENU_ data",
                            zeroRecords: "Tidak ada data yang ditemukan",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "Selanjutnya",
                                previous: "Sebelumnya"
                            }
                        },
                        ajax: {
                            url: "{{ route('tabel-dinamis.portal.data') }}",
                            data: d => {
                                d.search = keyword
                            },
                            complete: () => Swal.close()
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                orderable: false,
                                searchable: false,
                                className: 'px-6 py-4 text-center font-semibold border-b'
                            },
                            {
                                data: 'judul',
                                name: 'judul',
                                className: 'px-6 py-4 border-b'
                            },
                            {
                                data: 'tanggal',
                                name: 'tanggal',
                                className: 'px-6 py-4 text-center border-b'
                            }
                        ],
                        drawCallback: function() {
                            $('#statistik-table tbody tr').hover(
                                function() {
                                    $(this).addClass('bg-blue-50');
                                },
                                function() {
                                    $(this).removeClass('bg-blue-50');
                                }
                            );
                        },
                        initComplete: function() {
                            // Pindahkan lengthMenu ke #custom-length-statistik
                            const lengthMenu = $('.dataTables_length');
                            $('#custom-length-statistik').append(lengthMenu);

                            // Style dropdown
                            $('#custom-length-statistik label').addClass(
                                'flex items-center gap-1 text-xs text-gray-700');
                            $('#custom-length-statistik select').addClass(
                                'border border-gray-300 rounded px-1 py-0.5 text-xs min-w-[50px] focus:border-blue-500 focus:ring-1 focus:ring-blue-100'
                            );

                            // Hilangkan teks "Tampilkan ... data"
                            $('#custom-length-statistik label').contents().filter(function() {
                                return this.nodeType === 3;
                            }).remove();

                            // Style pagination
                            $('.dataTables_paginate').addClass('flex items-center gap-1 mt-2');
                            $('.dataTables_paginate .paginate_button').addClass(
                                'px-2 py-1 rounded text-xs text-gray-700 hover:bg-blue-100 transition'
                            );
                            $('.dataTables_paginate .paginate_button.current').addClass(
                                'bg-blue-600 text-white');
                        }
                    });

                } else {
                    statistikTable.ajax.reload(null, false);
                }
                $('#results-section, #statistik-container').removeClass('hidden');
            } else {
                if (!publikasiTable) {
                    publikasiTable = $('#publikasi-table').DataTable({
                        processing: true,
                        serverSide: true,
                        searching: false,
                        responsive: true,
                        pageLength: 25,
                        dom: "<'top-area' l>rt<'flex items-center justify-between px-6 py-2' ip>",
                        language: {
                            processing: '<div class="flex items-center justify-center py-4"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div><span class="ml-3">Memuat data...</span></div>',
                            lengthMenu: "Tampilkan _MENU_ data",
                            zeroRecords: "Tidak ada data yang ditemukan",
                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            paginate: {
                                first: "Pertama",
                                last: "Terakhir",
                                next: "Selanjutnya",
                                previous: "Sebelumnya"
                            }
                        },
                        ajax: {
                            url: "{{ route('siaga.portal.data') }}",
                            data: d => {
                                d.search = keyword
                            },
                            complete: () => Swal.close()
                        },
                        columns: [{
                                data: 'DT_RowIndex',
                                orderable: false,
                                searchable: false,
                                className: 'px-6 py-4 text-center font-semibold border-b'
                            },
                            {
                                data: 'judul_tabel',
                                name: 'judul_tabel',
                                className: 'px-6 py-4 border-b'
                            },
                            {
                                data: 'nomor_tabel',
                                name: 'nomor_tabel',
                                className: 'px-6 py-4 text-center border-b'
                            },
                            {
                                data: 'judul_publikasi',
                                name: 'judul_publikasi',
                                className: 'px-6 py-4 border-b'
                            },
                            {
                                data: 'nomor_halaman',
                                name: 'nomor_halaman',
                                className: 'px-6 py-4 text-center border-b'
                            },
                            {
                                data: 'link_output',
                                name: 'link_output',
                                className: 'px-6 py-4 text-center border-b'
                            }
                        ],
                        drawCallback: function() {
                            $('#publikasi-table tbody tr').hover(
                                function() {
                                    $(this).addClass('bg-blue-50');
                                },
                                function() {
                                    $(this).removeClass('bg-blue-50');
                                }
                            );
                        },
                        initComplete: function() {
                            // Pindahkan lengthMenu ke #custom-length
                            const lengthMenu = $('.dataTables_length');
                            $('#custom-length').append(lengthMenu);

                            // Style dropdown
                            $('#custom-length label').addClass('flex items-center gap-1 text-xs text-gray-700');
                            $('#custom-length select').addClass(
                                'border border-gray-300 rounded px-1 py-0.5 text-xs min-w-[50px] focus:border-blue-500 focus:ring-1 focus:ring-blue-100'
                            );

                            // Hilangkan teks "Tampilkan ... data"
                            $('#custom-length label').contents().filter(function() {
                                return this.nodeType === 3;
                            }).remove();

                            // Style pagination
                            $('.dataTables_paginate').addClass('flex items-center gap-1 mt-2');
                            $('.dataTables_paginate .paginate_button').addClass(
                                'px-2 py-1 rounded text-xs text-gray-700 hover:bg-blue-100 transition'
                            );
                            $('.dataTables_paginate .paginate_button.current').addClass(
                                'bg-blue-600 text-white');
                        }
                    });


                } else {
                    publikasiTable.ajax.reload(null, false);
                }
                $('#results-section, #publikasi-container').removeClass('hidden');
            }
        }

        function showModal(type) {
            const configs = {
                statistik: {
                    title: '📊 Tabel Statistik',
                    text: 'Tabel ini berisi data statistik yang dirilis secara berkala oleh instansi terkait. Cocok untuk analisis makro.'
                },
                publikasi: {
                    title: '📚 Tabel Publikasi',
                    text: 'Tabel ini berisi data publikasi dari berbagai sumber yang telah diverifikasi.'
                }
            };

            const config = configs[type];
            Swal.fire({
                title: config.title,
                text: config.text,
                icon: 'info',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#2563eb'
            });
        }

        $(document).ready(function() {
            $('#search-judul').focus();
        });
    </script>
@endpush
@endsection
