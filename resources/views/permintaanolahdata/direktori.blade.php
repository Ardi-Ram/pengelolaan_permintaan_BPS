@extends('layouts.app')
@section('title', 'Direktoris Permintaan Data')
@section('content')
    <div class="bg-white my-5 ml-5 mr-9 rounded-lg border border-gray-300">
        <h2 class="text-xl font-bold p-4 border-b border-gray-300 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-600">
                folder
            </span>
            <span>Direktori Data</span>
        </h2>


        <div class="p-3">
            {{-- Modal Backup --}}
            <div id="modal-backup" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-md">
                    <div class="flex justify-between items-center border-b p-4">
                        <h3 class="text-lg font-semibold">Pilih Bulan untuk Backup</h3>
                        <button onclick="document.getElementById('modal-backup').classList.add('hidden')"
                            class="text-gray-500 hover:text-gray-700">
                            ✕
                        </button>
                    </div>
                    <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                        @foreach ($months as $key => $label)
                            @php [$year, $month] = explode('-', $key); @endphp
                            <a href="{{ route('direktori.backup.byMonth', ['year' => $year, 'month' => $month]) }}"
                                class="flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition">
                                <span class="material-symbols-outlined mr-2">backup</span>
                                <span class="truncate">{{ $label }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="p-4 text-right">
                        <button onclick="document.getElementById('modal-backup').classList.add('hidden')"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Tutup</button>
                    </div>
                </div>
            </div>

            {{-- DataTable --}}
            <table id="direktori-table" class="min-w-full bg-white">
                <thead class=" text-gray-600 font-semibold text-xs uppercase">
                    <tr>
                        <th class="px-2 py-2">No</th>
                        <th class="px-2 py-2">Judul</th>
                        <th class="px-2 py-2">Kategori</th>
                        <th class="px-2 py-2">Petugas PST</th>
                        <th class="px-2 py-2">Periode</th>
                        <th class="px-2 py-2">Info Backup</th>
                        <th class="px-2 py-2">Aksi</th>
                    </tr>
                    <tr class="">
                        <th></th>
                        <th>
                            <div class="flex items-center gap-2" id="judul-toolbar">
                                <div id="length-container"></div>
                                <div id="search-container" class="flex-1"></div>
                            </div>
                        </th>
                        <th>
                            <select id="filter-kategori"
                                class="w-full max-w-[140px] border border-gray-300 px-2 py-1 rounded text-xs truncate">
                                <option value="">Semua</option>
                                @foreach ($kategoriList as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>

                        </th>
                        <th>
                            <select id="filter-petugas" class="w-full border border-gray-300 px-2 py-1 rounded text-xs">
                                <option value="">Semua</option>
                                @foreach ($petugasList as $petugas)
                                    <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                                @endforeach
                            </select>
                        </th>
                        <th>
                            <select id="filter-tahun" class="w-full border border-gray-300 px-2 py-1 rounded text-xs">
                                <option value="">Semua</option>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endforeach
                            </select>
                        </th>

                        <th>
                            <div class="">
                                <button
                                    class="flex items-center px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-150 text-xs"
                                    onclick="document.getElementById('modal-backup').classList.remove('hidden')">
                                    <span class="material-symbols-outlined mr-1 text-sm">backup</span>
                                    Backup
                                </button>
                            </div>
                        </th>
                        <th>
                            <!-- Trigger Button -->
                            <button onclick="openHapusModal()"
                                class="flex items-center gap-1 bg-red-600  border text-white rounded px-3 py-1  text-xs transition">
                                <span class="material-symbols-outlined text-base">delete</span>
                                <span>Hapus</span>
                            </button>

                            <!-- Modal -->
                            <div id="hapusModal"
                                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                                <div class="bg-white rounded-lg shadow p-6 w-full max-w-md">
                                    <h2 class="text-lg font-bold mb-4">Pilih Bulan Backup untuk Dihapus</h2>

                                    <form method="POST" action="{{ route('direktori.hapus.bulanan') }}">
                                        @csrf
                                        <label for="bulanBackup" class="block text-sm mb-1">Bulan Backup</label>
                                        <select name="bulan_tahun" id="bulanBackup" required
                                            class="w-full border px-2 py-1 rounded mb-4">
                                            <option value="">Memuat...</option>
                                        </select>

                                        <div class="flex justify-end gap-2">
                                            <button type="button" onclick="closeHapusModal()"
                                                class="px-4 py-1 border rounded">Batal</button>
                                            <button type="submit"
                                                class="bg-red-600 text-white px-4 py-1 rounded">Hapus</button>
                                        </div>
                                    </form>
                                </div>
                            </div>


                        </th>
                    </tr>
                </thead>


            </table>

        </div>
    </div>
    <script>
        function openHapusModal() {
            document.getElementById('hapusModal').classList.remove('hidden');

            // Load bulan dari server
            fetch('{{ route('direktori.backup.months') }}')
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById('bulanBackup');
                    select.innerHTML = '';

                    if (Object.keys(data).length === 0) {
                        select.innerHTML = '<option value="">Tidak ada backup tersedia</option>';
                    } else {
                        for (const [key, label] of Object.entries(data)) {
                            const option = document.createElement('option');
                            option.value = key; // e.g., "2025-08"
                            option.textContent = label;
                            select.appendChild(option);
                        }
                    }
                });
        }

        function closeHapusModal() {
            document.getElementById('hapusModal').classList.add('hidden');
        }
    </script>

    <script>
        $(function() {
            var table = $('#direktori-table').DataTable({ // ✅ simpan instance ke `table`
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('pengolah.direktori.data') }}',
                    data: function(d) {
                        d.kategori = $('#filter-kategori').val();
                        d.petugas = $('#filter-petugas').val();
                        d.tahun = $('#filter-tahun').val();
                    }
                },
                dom: 'lrftip',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-gray-400 text-sm',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul_permintaan',
                        name: 'judul_permintaan'
                    },
                    {
                        data: 'kategori',
                        name: 'kategori.nama_kategori'
                    },

                    {
                        data: 'petugas_pst',
                        name: 'petugas_pst'
                    },
                    {
                        data: 'periode',
                        name: 'created_at'
                    },
                    {
                        data: 'backup_info',
                        name: 'backup_info',
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
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('bg-white shadow-sm rounded-md');
                },
                initComplete: function() {

                    $('#direktori-table_filter').appendTo('#search-container');
                    $('#direktori-table_length').appendTo('#length-container');

                    $('#direktori-table_filter input')
                        .addClass('border border-gray-300 rounded px-2 py-1 text-xs w-full')
                        .attr('placeholder', 'Cari judul...');

                    $('#direktori-table_filter label').contents().filter(function() {
                        return this.nodeType === 3;
                    }).remove();

                    $('#direktori-table_length select')
                        .addClass('border border-gray-300 rounded px-2 py-1 text-xs bg-white');

                    $('#direktori-table_length label').contents().filter(function() {
                        return this.nodeType === 3;
                    }).remove();
                },

                language: {
                    search: "",
                    searchPlaceholder: "Cari permintaan...",
                    lengthMenu: "_MENU_",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    }
                }
            });


            $('#filter-kategori, #filter-petugas, #filter-tahun').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endsection
