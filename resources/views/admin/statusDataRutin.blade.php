@extends('layouts.admin')

@section('content')
    <div class="bg-white m-5 rounded-lg border border-gray-300">
        <h1 class="text-2xl font-bold text-gray-800 mb-6 p-5 border-b border-gray-300">Status Data Rutin</h1>

        <div class="overflow-x-auto bg-white border border-gray-300 m-5 rounded-lg pb-4">
            <table id="tabelRutin" class="min-w-full text-sm text-gray-800 ">
                <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3 text-left border-y border-gray-300">Judul</th>
                        <th class="px-6 py-3 text-left border-y border-gray-300">Tanggal Dibuat</th>
                        <th class="px-6 py-3 text-left border-y border-gray-300">Kode Data</th>
                        <th class="px-6 py-3 text-left border-y border-gray-300">Pengolah</th>
                        <th class="px-6 py-3 text-center border-y border-gray-300">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataRutin as $index => $item)
                        <tr class="border-b border-gray-300">
                            <td class="px-6 py-4 border-b border-gray-300">{{ $item->judul }}</td>
                            <td class="px-6 py-4 border-b border-gray-300 flex items-center gap-1">
                                <span class="material-symbols-outlined text-gray-500 text-base">
                                    calendar_today
                                </span>
                                {{ $item->created_at->format('d-m-Y') }}
                            </td>

                            <td class="px-6 py-4 border-b border-gray-300 font-medium text-blue-600">
                                {{ $item->kode_permintaan }}</td>
                            <td class="px-6 py-2 border-b border-gray-300">
                                @if ($item->pengolah)
                                    {{ $item->pengolah->name }}<br>
                                    <span class="text-sm text-gray-500">{{ $item->pengolah->email }}</span>
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-6 py-4 border-b border-gray-300 text-center">
                                <span
                                    class="px-3 py-1 rounded-md  text-xs font-semibold
                                {{ $item->status === 'antrian' ? 'bg-red-200  text-red-600' : ($item->status === 'proses' ? 'bg-yellow-400 text-black' : 'bg-gray-400') }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- SCRIPT --}}
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#tabelRutin').DataTable({
                    responsive: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Cari data...",
                        lengthMenu: "Tampilkan _MENU_ entri",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        },
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        zeroRecords: "Data tidak ditemukan",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 entri",
                        infoFiltered: "(disaring dari _MAX_ total entri)"
                    },
                    dom: "<'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4  mx-4 my-4'" +
                        "<'flex items-center gap-2'l>" +
                        "<'flex items-center gap-2'f>" +
                        ">" +
                        "<'overflow-x-auto'tr>" +
                        "<'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-4'" +
                        "<'text-sm text-gray-600'i>" +
                        "<'text-sm'p>" +
                        ">"
                });
            });
        </script>
    </div>
@endsection
