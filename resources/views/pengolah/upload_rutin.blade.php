@extends('layouts.pengolah')

@section('content')
    <div class="container mx-auto p-4">
        <h2 class="text-xl font-semibold mb-4">Upload Hasil Olahan - Permintaan Data Rutin</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded mb-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 text-red-700 p-2 rounded mb-3">
                {{ session('error') }}
            </div>
        @endif

        <table id="table-upload-rutin" class="min-w-full bg-white rounded shadow text-sm">
            <thead class="bg-gray-100 text-gray-600 uppercase">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table-upload-rutin').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('pengolah.rutin.upload.list') }}',
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
                        name: 'kategori.nama_kategori'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'aksi',
                        name: 'aksi',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
