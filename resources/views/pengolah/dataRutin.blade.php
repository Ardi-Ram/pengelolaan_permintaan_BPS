@extends('layouts.pengolah')

@section('content')
    <div class=" px-4 py-2 flex justify-start gap-3  rounded-md m-5">
        <!-- Link: Permintaan Biasa -->
        <a href="{{ route('pengolah.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border
    {{ Request::routeIs('pengolah.index')
        ? 'bg-blue-700 text-white border-blue-700'
        : 'bg-white text-blue-700 border-blue-500 hover:bg-blue-50' }}">
            <span class="material-symbols-outlined text-base">
                description
            </span>
            Permintaan Biasa
        </a>

        <!-- Link: Permintaan Rutin -->
        <a href="{{ route('pengolah.rutin') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border
    {{ Request::routeIs('pengolah.rutin.index')
        ? 'bg-green-700 text-white border-green-700'
        : 'bg-white text-green-700 border-green-500 hover:bg-green-50' }}">
            <span class="material-symbols-outlined text-base">
                schedule
            </span>
            Permintaan Rutin
        </a>


    </div>

    <div class="bg-white border border-gray-200 m-5 p-6 rounded-lg">
        <form id="bulk-apply-form" method="POST" action="{{ route('permintaan.rutin.bulk-apply') }}">
            @csrf
            <input type="hidden" name="ids" id="bulk-ids">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 mb-3 rounded hover:bg-green-700 text-sm">
                Apply Terpilih
            </button>
        </form>

        <table id="data-rutin" class="min-w-full text-sm bg-white m-5 border border-gray-200">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Kode Permintaan</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Tanggal Dibuat</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

        </table>
    </div>

    <script>
        $(document).ready(function() {
            const table = $('#data-rutin').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('pengolah.rutin.data') }}',
                lengthMenu: [
                    [10, 25, 50, 100],
                    ['10', '25', '50', '100']
                ],
                language: {
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    search: "",
                    searchPlaceholder: "Cari data..."
                },
                dom: '<"top flex flex-col sm:flex-row justify-between items-center gap-4 mb-4"<"length-wrapper"l><"search-wrapper"f>>' +
                    'rt' +
                    '<"bottom flex flex-col sm:flex-row justify-between items-center gap-4 mt-4"<"info-wrapper"i><"pagination-wrapper"p>>',
                columns: [{
                        data: 'checkbox',
                        name: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_permintaan',
                        name: 'kode_permintaan'
                    },
                    {
                        data: 'judul',
                        name: 'judul'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ],
                initComplete: function() {
                    // Style LengthMenu
                    $('.dataTables_length').addClass('flex items-center gap-2 text-sm text-gray-700');
                    $('.dataTables_length label').addClass('flex items-center gap-2 font-medium');
                    $('.dataTables_length select').addClass(
                        'border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-24 p-2.5'
                    );

                    // Style SearchBox
                    $('.dataTables_filter').addClass('relative text-sm text-gray-700');
                    $('.dataTables_filter label').addClass('flex items-center gap-2 font-medium');
                    $('.dataTables_filter input').addClass(
                        'border border-gray-300 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5'
                    );

                    // Style Pagination
                    $('.dataTables_paginate').addClass('text-sm mt-4');
                    $('.paginate_button').addClass(
                        'px-3 py-1 border border-gray-300 text-gray-700 rounded hover:bg-gray-100');

                    // Style Info
                    $('.dataTables_info').addClass('text-sm text-gray-600');
                },
                drawCallback: function() {
                    // Tambahkan border bawah di setiap baris tabel
                    $('#data-rutin tbody tr').addClass('border-b border-gray-200');

                    // Styling pagination setiap kali data di-refresh
                    const pagination = $('.dataTables_paginate');

                    pagination.find('a').each(function() {
                        const $btn = $(this);

                        // Reset semua class dulu agar bersih
                        $btn.removeClass();

                        // Tambahkan styling Flowbite-like
                        $btn.addClass(
                            'px-3 py-1 mx-1 border border-gray-300 text-sm rounded hover:bg-gray-100 transition'
                        );

                        // Jika tombol aktif (current page)
                        if ($btn.hasClass('current')) {
                            $btn.addClass('bg-blue-500 text-white hover:bg-blue-600');
                        } else {
                            $btn.addClass('text-gray-700');
                        }
                    });
                },
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('border-b border-gray-200');
                },


            });
        });
    </script>
    <script>
        $('#bulk-apply-form').on('submit', function(e) {
            e.preventDefault();

            let selectedIds = [];
            $('input.checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                alert('Pilih minimal satu permintaan!');
                return false;
            }

            $('#bulk-ids').val(selectedIds.join(','));

            this.submit();
        });
    </script>
@endsection
