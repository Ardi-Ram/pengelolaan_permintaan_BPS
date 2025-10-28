@extends('layouts.app')

@section('title', 'Daftar Tabel Statistik')

@section('content')
    <div class="flex-1 m-5 border border-gray-300 rounded-lg bg-white">
        <h1 class="border-b border-gray-300 text-2xl font-bold flex items-center p-4 shadow-sm">
            <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 mr-2 text-blue-600">
                <span class="material-symbols-outlined  text-[24px] text-blue-600">table_chart</span>
            </span>

            Tabel Statistik Ditugaskan
        </h1>

        <div class="max-w-7xl mx-auto">

            {{-- NOTIFIKASI TOASTR --}}
            @if (session('success'))
                <script>
                    window.onload = () => toastr.success("{{ session('success') }}");
                </script>
            @elseif (session('error'))
                <script>
                    window.onload = () => toastr.error("{{ session('error') }}");
                </script>
            @endif

            <div class="overflow-x-auto rounded-lg m-5 border border-gray-300">
                <div id="custom-controls" class="flex justify-end items-center gap-4 px-5 py-3 flex-wrap">
                    <div id="custom-length"></div>
                    <div>
                        <select id="filter-kategori"
                            class="pl-3 pr-8 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div id="custom-search"></div>
                </div>

                <table id="pengolah-table" class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700">
                    <thead class=" text-gray-600 font-semibold text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-center">No</th>
                            <th class="px-4 py-2">Judul</th>
                            <th class="px-4 py-2">Kategori</th>
                            <th class="px-4 py-2">Petugas PST</th>
                            <th class="px-4 py-2">Waktu Dibutuhkan</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL TOLAK --}}
    <div id="modalTolak" class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-md shadow-xl border border-gray-200 relative">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Tolak Tugas Statistik</h2>

            <form id="tolakForm" method="POST" action="">
                @csrf
                <textarea name="alasan_penolakan" required
                    class="w-full h-24 border border-gray-300 rounded-md p-2 text-sm mb-4 focus:outline-none focus:ring focus:border-blue-400"
                    placeholder="Tuliskan alasan penolakan..."></textarea>

                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeTolakModal()"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                        Kirim Penolakan
                    </button>
                </div>
            </form>

        </div>
    </div>
    <script>
        function openTolakModal(id) {
            const form = document.getElementById('tolakForm');
            form.action = `/tabel-dinamis/${id}/tolak`; // Route tolak
            document.getElementById('modalTolak').classList.remove('hidden');
        }

        function closeTolakModal() {
            document.getElementById('modalTolak').classList.add('hidden');
        }

        $(document).ready(function() {
            const table = $('#pengolah-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tabeldinamis.data') }}",
                    data: d => {
                        d.kategori_id = $('#filter-kategori').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-center text-gray-400',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        className: "px-4 py-2"
                    },
                    {
                        data: 'kategori',
                        className: "px-4 py-2"
                    },
                    {
                        data: 'petugas_pst',
                        name: 'petugas_pst'
                    },
                    {
                        data: 'deadline',
                        className: "px-4 py-2"
                    },
                    {
                        data: 'status',
                        className: "px-4 py-2"
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: "px-4 py-2"
                    },
                ],
                language: {
                    search: "",
                    searchPlaceholder: "Cari judul...",
                    lengthMenu: "_MENU_",
                    paginate: {
                        previous: `<span class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">←</span>`,
                        next: `<span class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">→</span>`
                    }
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass(
                        'border-b border-gray-200 bg-white shadow-sm rounded-md');
                    $('td', row).addClass('px-4 py-3 text-sm text-gray-700');
                },
                dom: 'lftrip',
                initComplete: function() {
                    $('#custom-length').append($('.dataTables_length select').addClass(
                        'pl-3 pr-8 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500'
                    ));
                    $('#custom-search').append($('.dataTables_filter input').addClass(
                        'pl-3 pr-3 py-1.5 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500'
                    ));
                    $('#filter-kategori').on('change', function() {
                        table.ajax.reload();
                    });
                },
                drawCallback: function() {
                    // Pagination styling
                    $('.dataTables_paginate').addClass('m-4 flex justify-center gap-1');
                    $('.dataTables_paginate a').addClass(
                        'px-3 py-1 rounded border text-sm text-blue-600 hover:bg-blue-100 transition'
                    );
                    $('.dataTables_paginate .current').addClass(
                        'bg-blue-500 text-white border-blue-500'
                    );

                    // Tombol Apply
                    $('.apply-btn').off('click').on('click', function() {
                        const id = $(this).data('id');
                        if (!id) return;

                        Swal.fire({
                            title: 'Yakin ingin apply tugas ini?',
                            text: "Setelah apply, tugas ini akan berpindah ke status proses.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#aaa',
                            confirmButtonText: 'Ya, apply sekarang',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: `/tabel-dinamis/apply/${id}`,
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function(response) {
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: 'Tugas berhasil di-apply.',
                                            icon: 'success',
                                            timer: 2000,
                                            showConfirmButton: false
                                        });
                                        $('#pengolah-table').DataTable()
                                            .ajax.reload(null, false);
                                    },
                                    error: function(xhr) {
                                        const message = xhr.responseJSON
                                            ?.message ||
                                            "Gagal apply tugas.";
                                        Swal.fire({
                                            title: 'Error!',
                                            text: message,
                                            icon: 'error',
                                        });
                                    }
                                });
                            }
                        });
                    });

                }
            });
        });
    </script>
@endsection
