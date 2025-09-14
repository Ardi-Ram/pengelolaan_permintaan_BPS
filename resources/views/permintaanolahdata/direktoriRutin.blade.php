@php
    $layout = Auth::user()->hasRole('petugas_pst') ? 'layouts.petugas' : 'layouts.pengolah';
@endphp

@extends($layout)
@section('title', 'Direktori Data Rutin')

@section('content')
    <div class=" px-4 py-2 flex justify-start gap-3  rounded-md m-5">
        <!-- Link: Permintaan Biasa -->
        <a href="{{ route('pengolah.direktori.view') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border
    {{ Request::routeIs('pengolah.index')
        ? 'bg-blue-700 text-white border-blue-700'
        : 'bg-white text-blue-700 border-blue-500 hover:bg-blue-50' }}">
            <span class="material-symbols-outlined text-base">
                description
            </span>
            Direktori Data Customer
        </a>

        <!-- Link: Permintaan Rutin -->
        <a href="{{ route('pengolah.direktori.rutin.view') }}"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-md border
    {{ Request::routeIs('pengolah.rutin.index')
        ? 'bg-green-700 text-white border-green-700'
        : 'bg-white text-green-700 border-green-500 hover:bg-green-50' }}">
            <span class="material-symbols-outlined text-base">
                schedule
            </span>
            Direktori Data Rutin
        </a>


    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Direktori Data Rutin</h1>

        <div class="bg-white shadow rounded-lg p-4">
            <table id="table-direktori-rutin" class="min-w-full text-sm border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="px-4 py-2 border">#</th>
                        <th class="px-4 py-2 border">Judul</th>
                        <th class="px-4 py-2 border">Kategori</th>
                        <th class="px-4 py-2 border">Tanggal</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#table-direktori-rutin').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('pengolah.direktori.rutin.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'tanggal',
                        name: 'tanggal'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    },
                ]
            });
        });
    </script>

@endsection
