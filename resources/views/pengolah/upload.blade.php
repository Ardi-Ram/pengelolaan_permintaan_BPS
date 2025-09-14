@extends('layouts.pengolah')

@section('content')
    <div class="max-w-full m-5 border border-gray-300 rounded-lg bg-white">
        <h1 class="border-b border-gray-300 text-2xl font-bold flex items-center p-4 shadow-sm">
            <span class="material-symbols-outlined mr-2 text-[28px]">folder</span>
            Upload Data
        </h1>

        {{-- Notifikasi --}}
        @if ($errors->any())
            <div class="mb-4 p-4 rounded bg-red-100 text-red-800 mx-5">
                <ul class="list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mx-5 my-4 text-sm">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mx-5 my-4 text-sm">
                <strong>Gagal!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg m-6">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-6 py-3 bg-gray-50">No</th>
                        <th class="px-6 py-3">Judul</th>
                        <th class="px-6 py-3 bg-gray-50">Kategori</th>
                        <th class="px-6 py-3">Petugas PST</th>
                        <th class="px-6 py-3 bg-gray-50">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr class="border-b border-gray-200">
                            <td class="px-6 py-4 bg-gray-50">
                                {{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 font-medium text-gray-900">
                                    <span class="material-symbols-outlined text-blue-600 text-base">description</span>
                                    {{ $item->judul_permintaan }}
                                </div>
                            </td>
                            <td class="px-6 py-4 bg-gray-50">
                                {{ $item->kategori->nama_kategori ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $item->petugasPst->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 bg-gray-50">
                                <div class="flex items-center gap-2">
                                    @if ($item->status === 'selesai' && optional($item->hasilOlahan)->verifikasi_hasil === null)
                                        <span class="text-sm text-gray-500 italic">Menunggu verifikasi...</span>
                                    @elseif (optional($item->hasilOlahan)->verifikasi_hasil === 'tidak_valid')
                                        <button type="button"
                                            onclick="showCatatan(`{{ $item->hasilOlahan->catatan_verifikasi }}`)"
                                            class="flex items-center gap-1 bg-red-100 text-red-800 px-3 py-1 text-sm rounded hover:bg-red-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 9v2m0 4h.01m-.01-8h.01m-.01 0h.01m-.01 8h.01m6-6a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Tidak Valid
                                        </button>
                                    @else
                                        <button type="button" onclick="openUploadModal({{ $item->id }})"
                                            class="flex items-center gap-1 bg-green-600 text-white px-3 py-1 text-sm rounded hover:bg-green-700">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v6m0 0l-3-3m3 3l3-3m0-6a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            Upload
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center px-6 py-4 text-gray-500">Tidak ada data tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="flex justify-between items-center p-4 text-sm text-gray-600">
                <div>
                    Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data
                </div>
                <div>
                    {{ $data->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
            </div>
        </div>

        {{-- Upload Modal --}}
        <div id="uploadModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-40 flex items-center justify-center">
            <div class="bg-white w-full max-w-md rounded-lg shadow-lg p-6 relative">
                <button onclick="closeUploadModal()" class="absolute top-2 right-3 text-gray-500 hover:text-gray-700">
                    ✖
                </button>
                <h2 class="text-lg font-bold mb-4 text-gray-700 flex items-center gap-2">
                    <span class="material-symbols-outlined">upload</span>
                    Upload Hasil Olahan
                </h2>
                <p class="text-sm text-gray-600 mb-4">
                    File yang diunggah harus berformat: <strong>.xls, .xlsx, atau .csv</strong><br>
                    Ukuran maksimal file: <strong>5 MB</strong>.
                </p>
                <form method="POST" action="#" enctype="multipart/form-data" id="uploadForm">
                    @csrf
                    <input type="file" name="file_hasil" accept=".xls,.xlsx,.csv" required
                        class="w-full mb-3 border border-gray-300 rounded px-3 py-2 text-sm file:bg-blue-50 file:text-blue-800">
                    <button type="submit"
                        class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 text-sm font-semibold">
                        Upload Sekarang
                    </button>
                </form>
            </div>
        </div>

        <script>
            function openUploadModal(permintaanId) {
                document.getElementById('uploadModal').classList.remove('hidden');
                document.getElementById('uploadForm').action =
                    `/pengolah/permintaan/${permintaanId}/upload`; // pastikan route ini sesuai
            }

            function closeUploadModal() {
                document.getElementById('uploadModal').classList.add('hidden');
            }
        </script>


        {{-- Modal Catatan --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            let uploadForm = document.getElementById('uploadForm');

            function openUploadModal(permintaanId) {
                uploadForm.action = `{{ url('pengolah/permintaan/upload') }}/${permintaanId}`;
                document.getElementById('uploadModal').classList.remove('hidden');
            }

            function closeUploadModal() {
                document.getElementById('uploadModal').classList.add('hidden');
            }

            function showCatatan(catatan) {
                Swal.fire({
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
                    </div>`,
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
                $('#uploadTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('pengolah.upload.dataTable') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
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
                            data: 'petugas',
                            name: 'petugasPst.name'
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
