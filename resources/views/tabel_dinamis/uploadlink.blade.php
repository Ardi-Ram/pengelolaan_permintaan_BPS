@extends('layouts.pengolah')

@section('content')
    <div class="flex-1 m-5 border border-gray-300 rounded-lg bg-white">
        <h1 class="border-b border-gray-300 text-2xl font-bold flex items-center p-4 shadow-sm">
            <span class="material-symbols-outlined mr-2 text-[28px]">link</span>
            Upload Link Tabel Statistik
        </h1>

        <div class="max-w-7xl mx-auto">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto rounded-lg m-5 ">
                <div id="custom-controls" class="flex justify-end items-center gap-4 px-5 py-3 flex-wrap">
                    <div id="custom-length"></div>

                    <div id="custom-search"></div>
                </div>

                <table id="upload-table"
                    class="min-w-full divide-y divide-gray-200 text-sm text-left text-gray-700 odd:bg-white even:bg-gray-50">

                    <thead class=" text-gray-600 font-semibold text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2 text-center">No</th>
                            <th class="px-4 py-2">Judul</th>
                            <th class="px-4 py-2">Kategori</th>
                            <th class="px-4 py-2">Petugas PST</th>
                            <th class="px-4 py-2">Tanggal Rilis</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Upload Link -->
    <div id="upload-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 justify-center items-center">
        <div class="bg-white rounded-lg p-6 w-full max-w-md shadow">
            <h2 class="text-lg font-bold mb-4">Upload Link</h2>
            <form id="upload-form">
                @csrf
                <input type="hidden" id="upload-id">
                <div class="mb-4">
                    <label for="link_hasil" class="block mb-1 text-sm">Link Hasil</label>
                    <input type="url" id="link_hasil" name="link_hasil"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancel-btn"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function showCatatan(catatan) {
            Swal.fire({
                title: '',
                html: `
                <div class="flex items-center gap-2 text-lg font-semibold text-gray-700 mb-4">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                    <span>Catatan Verifikasi</span>
                </div>

                <div class="p-5 h-48 overflow-y-auto border border-gray-300 bg-gray-50 rounded-lg shadow-sm font-serif text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                    ${catatan}
                </div>
            `,
                customClass: {
                    popup: 'w-[600px]',
                    confirmButton: 'bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm mt-4'
                },
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: 'Tutup'
            });
        }
    </script>


    <script>
        $(document).ready(function() {
            const table = $('#upload-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('tabeldinamis.upload.data') }}",
                    data: d => {
                        d.kategori_id = $('#filter-kategori').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        className: 'text-gray-400 text-sm text-center ',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul',
                        className: "px-4 py-2 "
                    },
                    {
                        data: 'kategori',
                        className: "px-4 py-2 "
                    },
                    {
                        data: 'petugas_pst',
                        className: "px-4 py-2 "
                    },
                    {
                        data: 'deadline',
                        className: "px-4 py-2 "
                    },
                    {
                        data: 'status_tampilan',
                        orderable: false,
                        searchable: false,
                        className: "px-4 py-2 "
                    },
                    {
                        data: 'aksi',
                        orderable: false,
                        searchable: false,
                        className: "px-4 py-2 "
                    }
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass(
                        'border-b border-gray-200 bg-white even:bg-gray-50 shadow-md rounded-md');
                    $('td', row).addClass('px-4 py-3 text-sm text-gray-700');
                },
                language: {
                    search: "",
                    searchPlaceholder: "Cari judul...",
                    lengthMenu: " _MENU_ ",
                    paginate: {
                        previous: `<span class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">←</span>`,
                        next: `<span class="px-3 py-1 rounded bg-gray-100 hover:bg-gray-200 text-sm">→</span>`
                    }
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
                    $('.dataTables_paginate').addClass('mt-4 flex justify-center gap-1');
                    $('.dataTables_paginate a').addClass(
                        'px-3 py-1 rounded border text-sm text-blue-600 hover:bg-blue-100 transition'
                    );
                    $('.dataTables_paginate .current').addClass(
                        'bg-blue-500 text-white border-blue-500');
                }
            });

            // Buka modal
            $(document).on('click', '.upload-btn', function() {
                $('#upload-id').val($(this).data('id'));
                $('#link_hasil').val('');
                $('#upload-modal').removeClass('hidden').addClass('flex');
            });

            // Batal
            $('#cancel-btn').click(() => $('#upload-modal').addClass('hidden'));

            // Submit upload
            $('#upload-form').submit(function(e) {
                e.preventDefault();
                const id = $('#upload-id').val();
                const data = {
                    _token: '{{ csrf_token() }}',
                    link_hasil: $('#link_hasil').val()
                };

                const url = "{{ route('tabeldinamis.upload.link', ':id') }}".replace(':id', id);


                $.post(url, data, res => {
                    if (res.success) {
                        $('#upload-modal').addClass('hidden');
                        table.ajax.reload();
                        alert('✅ Link berhasil diunggah.');
                    } else {
                        alert('❌ Gagal upload. Coba lagi.');
                    }
                });
            });

        });
    </script>
@endsection
