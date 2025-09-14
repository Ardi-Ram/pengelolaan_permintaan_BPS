@extends('layouts.pengolah')

@section('title', 'Penugasan Tabel SIAGA')

@section('content')
    <div class="bg-white m-5 rounded-lg border border-gray-300">
        <div class="flex-1">
            <h1 class="text-2xl font-bold flex items-center p-4 border-b border-gray-300">
                <span class="material-symbols-outlined mr-2 text-[28px]">assignment_ind</span>
                Penugasan Tabel Publikasi
            </h1>

            <div class="max-w-7xl mx-auto px-5 py-5">
                <div class="overflow-x-auto">
                    <table id="siaga-table"
                        class="min-w-full border-separate border-spacing-y-2 text-sm text-left text-gray-700">
                        <thead class="bg-blue-50 text-gray-600 font-semibold text-xs uppercase">
                            <tr>
                                <th class="px-4 py-3">No Publikasi</th>
                                <th class="px-4 py-3">Judul Publikasi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Petugas PST</th>
                                <th class="px-4 py-3">Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>

            {{-- Modal Penugasan --}}
            {{-- Modal Penugasan --}}
            <div id="modal-penugasan"
                class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 backdrop-blur-sm transition-all duration-300 ease-in-out">

                <div class="flex items-center justify-center h-full">
                    <div
                        class="relative bg-white w-full max-w-lg rounded-xl shadow-lg border border-gray-200 p-6 animate__animated animate__fadeInDown">
                        {{-- Header --}}
                        <div class="flex items-center justify-between mb-4 border-b pb-2">
                            <div class="flex items-center gap-2 text-blue-600">
                                <span class="material-symbols-outlined">assignment_ind</span>
                                <h2 class="text-lg font-bold">Penugasan Tabel Publikasi</h2>
                            </div>
                            <button id="close-modal" class="text-gray-500 hover:text-red-500">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>

                        {{-- Form --}}
                        <form id="form-penugasan" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="tabel_id" id="tabel_id">

                            <div>
                                <label for="petugas_id" class="block text-sm font-semibold text-gray-700 mb-1">
                                    <span
                                        class="material-symbols-outlined align-middle text-base mr-1 text-blue-500">group</span>
                                    Pilih Petugas PST
                                </label>
                                <select name="petugas_id" id="petugas_id"
                                    class="select2 w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:border-blue-400"
                                    required>
                                    <option value="">-- Pilih Petugas --</option>
                                    @foreach ($petugasList as $petugas)
                                        <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex justify-end gap-3 pt-3 border-t">
                                <button type="button" id="close-modal-footer"
                                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">cancel</span> Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">check_circle</span> Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Modal Detail --}}
            <div id="modal-detail"
                class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                <div class="bg-white max-w-4xl w-full p-6 rounded-lg shadow-lg overflow-auto max-h-[90vh]">
                    <h2 class="text-lg font-bold mb-4">Detail Tabel Publikasi</h2>
                    <table class="w-full border text-sm" id="tabel-detail-body">
                        <thead class="bg-gray-100">
                            <tr>
                                <th>No Tabel</th>
                                <th>Judul Tabel</th>
                                <th>Halaman</th>
                                <th>Status</th>
                                <th>Link</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                    <div class="text-right mt-4">
                        <button id="close-detail" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300">Tutup</button>
                    </div>
                </div>
            </div>

            {{-- Script --}}
            <script>
                document.addEventListener('DOMContentLoaded', function() {

                    let judulDipilih = '';

                    // Inisialisasi DataTables
                    const table = $('#siaga-table').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "{{ route('siaga.penugasan') }}",
                        columns: [{
                                data: 'nomor_publikasi',
                                name: 'nomor_publikasi'
                            },
                            {
                                data: 'judul_publikasi',
                                name: 'judul_publikasi'
                            },
                            {
                                data: 'status',
                                name: 'status',
                                orderable: false,
                                searchable: false
                            }, {
                                data: 'petugas_pst',
                                name: 'petugas_pst',
                                orderable: false,
                                searchable: false
                            },
                            {
                                data: 'aksi',
                                name: 'aksi',
                                orderable: false,
                                searchable: false
                            }
                        ],
                        createdRow: function(row) {
                            $(row).addClass('bg-white shadow-sm rounded-md');
                        },
                        dom: '<"hidden"l><"hidden"f>t<"flex justify-between items-center mt-4"ip>',
                        language: {
                            search: "",
                            searchPlaceholder: "Cari judul publikasi...",
                            lengthMenu: "Tampilkan _MENU_ entri",
                            paginate: {
                                previous: "Sebelumnya",
                                next: "Berikutnya"
                            }
                        }
                    });
                    // Detail Modal
                    $(document).on('click', '.btn-detail', function() {
                        const judul = $(this).data('judul');
                        $.get(`/siaga-tabel/detail/${encodeURIComponent(judul)}`, function(data) {
                            const tbody = $('#tabel-detail-body tbody');
                            tbody.empty();

                            data.forEach(row => {
                                tbody.append(`
                <tr class="border-b">
                    <td class="px-2 py-1">${row.nomor_tabel}</td>
                    <td class="px-2 py-1">${row.judul_tabel}</td>
                    <td class="px-2 py-1">${row.nomor_halaman}</td>
                    <td class="px-2 py-1 capitalize">${row.status}</td>
                    <td class="px-2 py-1">
                        ${row.link_output 
                            ? `<a href="${row.link_output}" target="_blank" class="text-blue-600 underline">Lihat</a>` 
                            : `<span class="text-gray-400 italic">Belum ada</span>`}
                    </td>
                </tr>
            `);
                            });

                            $('#modal-detail').removeClass('hidden');
                        });
                    });

                    // Tutup Modal
                    $('#close-detail').click(() => {
                        $('#modal-detail').addClass('hidden');
                    });

                    // Buka modal penugasan
                    $(document).on('click', '.btn-modal', function() {
                        judulDipilih = $(this).data('judul');
                        $('#modal-penugasan').removeClass('hidden');
                    });

                    // Tutup modal
                    $('#close-modal, #close-modal-footer').click(function() {
                        $('#modal-penugasan').addClass('hidden');
                    });

                    // Submit form penugasan
                    $('#form-penugasan').submit(function(e) {
                        e.preventDefault();

                        const petugasId = $('#petugas_id').val();

                        if (!petugasId) {
                            alert('Silakan pilih petugas PST terlebih dahulu.');
                            return;
                        }

                        $.post(`{{ route('siaga.penugasan.publikasi') }}`, {
                            _token: '{{ csrf_token() }}',
                            judul_publikasi: judulDipilih,
                            petugas_id: petugasId
                        }, function(res) {
                            $('#modal-penugasan').addClass('hidden');
                            table.ajax.reload();
                            alert(res.message);
                        }).fail(function(err) {
                            alert('Terjadi kesalahan saat menyimpan penugasan.');
                        });
                    });

                    // Inisialisasi Select2 di dalam modal
                    $('#petugas_id').select2({
                        dropdownParent: $('#modal-penugasan'),
                        placeholder: '-- Pilih Petugas PST --',
                        allowClear: true,
                        width: '100%'
                    });

                    $(document).on('click', '.btn-batal', function() {
                        const judul = $(this).data('judul');

                        if (confirm('Yakin ingin membatalkan penugasan untuk publikasi ini?')) {
                            $.post({
                                url: '/penugasan/batalkan',
                                data: {
                                    judul_publikasi: judul,
                                    _token: $('meta[name="csrf-token"]').attr('content')
                                },
                                success: function(res) {
                                    alert(res.message);
                                    $('#datatable').DataTable().ajax.reload(); // reload DataTables
                                },
                                error: function(err) {
                                    alert('Gagal membatalkan penugasan.');
                                }
                            });
                        }
                    });



                });
            </script>

        </div>
    </div>
@endsection
