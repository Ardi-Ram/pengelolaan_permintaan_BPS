@extends('layouts.app')
@section('title', 'Penugasan tabel publikasi')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6 mb-8">
            <h1 class="text-2xl font-bold mb-6 flex items-center gap-3 text-gray-800">
                <span class="material-symbols-outlined text-blue-600 text-3xl">assignment</span>
                Daftar Tugas Tabel Publikasi
            </h1>


            <div class="overflow-x-auto">
                <table id="pst-siaga-table" class="w-full text-sm text-left table-auto">
                    <thead class="bg-blue-50 text-gray-700 font-semibold text-xs uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-4 rounded-tl-lg">No</th>
                            <th class="py-3 px-4">Judul Publikasi</th>
                            <th class="py-3 px-4">Pengolah</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4 rounded-tr-lg">Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="modal-detail" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center p-4">
        <div
            class="bg-white rounded-lg shadow-xl max-w-4xl w-full p-6 transform transition-all scale-100 opacity-100 overflow-y-auto max-h-[90vh]">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Detail Tabel Publikasi</h2>
                <button id="close-detail-top" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="overflow-x-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                                Tabel</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                                Tabel</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Halaman</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tabel-detail-body">
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-6">
                <button id="close-detail"
                    class="bg-gray-200 text-gray-800 px-5 py-2 rounded-md hover:bg-gray-300 transition-colors duration-200">Tutup</button>
            </div>
        </div>
    </div>

    <div id="modal-upload" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex justify-center items-center p-4">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md p-6 transform transition-all scale-100 opacity-100">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800" id="modal-upload-title">Upload Link Publikasi</h2>
                <button id="close-upload-top" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form id="form-upload-link">
                @csrf
                <input type="hidden" id="upload-judul" name="judul_publikasi">
                <div class="mb-5">
                    <label for="link" class="block text-sm font-semibold text-gray-700 mb-1">Link Publikasi</label>
                    <input type="url" id="link" name="link" required autocomplete="off" {{-- Menambahkan autocomplete off --}}
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-gray-900 text-sm">
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" id="close-upload"
                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors duration-200">Batal</button>
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200"
                        id="submit-btn">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            const table = $('#pst-siaga-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('siaga.pst.data') }}",
                searching: false,
                lengthChange: false,

                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'judul_publikasi',
                        name: 'judul_publikasi'
                    },
                    {
                        data: 'pengolah',
                        name: 'pengolah'
                    },
                    {
                        data: 'status_label',
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
                // Menambahkan kelas untuk setiap baris yang dibuat oleh DataTables
                createdRow: function(row) {
                    $(row).addClass('bg-white shadow-sm rounded-lg hover:bg-gray-50'); // Efek hover
                },
                // Menambahkan kelas untuk setiap sel dalam baris
                columnDefs: [{
                    targets: '_all', // Terapkan ke semua kolom
                    className: 'px-4 py-3 border-b border-gray-100' // Padding dan border bawah
                }],
                // Atur bahasa dan fitur DataTables lainnya jika perlu
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json' // Menggunakan bahasa Indonesia
                }
            });

            // --- Event Listener untuk Tombol-tombol Aksi ---

            // Event klik untuk tombol "Detail"
            $(document).on('click', '.btn-detail', function() {
                const judul = $(this).data('judul'); // Ambil judul publikasi dari atribut data-judul
                if (!judul) {
                    console.error("Judul publikasi tidak ditemukan di tombol detail.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Judul publikasi tidak ditemukan. Harap refresh halaman.',
                    });
                    return; // Hentikan eksekusi jika judul kosong
                }

                // Kirim request AJAX untuk mendapatkan detail tabel
                $.get(`/siaga-tabel/detail/${encodeURIComponent(judul)}`, function(data) {
                    const tbody = $('#tabel-detail-body'); // ID tabel detail dipindahkan ke tbody
                    tbody.empty(); // Kosongkan isi tbody sebelum mengisi yang baru

                    if (data.length > 0) {
                        data.forEach(row => {
                            tbody.append(`
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">${row.nomor_tabel}</td>
                                    <td class="px-4 py-2 border">${row.judul_tabel}</td>
                                    <td class="px-4 py-2 border">${row.nomor_halaman}</td>
                                    <td class="px-4 py-2 border capitalize">${row.status}</td>
                                    <td class="px-4 py-2 border">
                                        ${row.link_output
                                            ? `<a href="${row.link_output}" target="_blank" class="text-blue-600 hover:underline">Lihat Link</a>`
                                            : `<span class="text-gray-400 italic">Belum ada</span>`}
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        tbody.append(`
                            <tr>
                                <td colspan="5" class="px-4 py-2 text-center text-gray-500">Tidak ada detail tabel untuk publikasi ini.</td>
                            </tr>
                        `);
                    }

                    // Tampilkan modal detail dengan efek fade-in
                    $('#modal-detail').removeClass('hidden').addClass('flex');
                    setTimeout(() => {
                        $('#modal-detail > div').removeClass('scale-0 opacity-0').addClass(
                            'scale-100 opacity-100');
                    }, 50); // Sedikit delay agar transisi terlihat
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    console.error("Gagal mengambil detail:", textStatus, errorThrown, jqXHR
                        .responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat detail. Silakan coba lagi. (Lihat konsol browser untuk info lebih lanjut)',
                    });
                });
            });

            // Event klik untuk menutup modal detail (tombol bawah dan tombol X atas)
            $('#close-detail, #close-detail-top').click(() => {
                $('#modal-detail > div').removeClass('scale-100 opacity-100').addClass('scale-0 opacity-0');
                setTimeout(() => {
                    $('#modal-detail').removeClass('flex').addClass('hidden');
                }, 300); // Sesuaikan dengan durasi transisi
            });


            // Event klik untuk tombol "Upload"
            $(document).on('click', '.btn-upload', function() {
                const judul = $(this).data('judul');
                $('#upload-judul').val(judul); // Isi hidden input dengan judul
                $('#link').val(''); // Kosongkan input link
                $('#modal-upload-title').text('Upload Link Publikasi'); // Set judul modal

                // Tampilkan modal upload/edit dengan efek fade-in
                $('#modal-upload').removeClass('hidden').addClass('flex');
                setTimeout(() => {
                    $('#modal-upload > div').removeClass('scale-0 opacity-0').addClass(
                        'scale-100 opacity-100');
                }, 50);
            });

            // Event klik untuk tombol "Edit Link"
            $(document).on('click', '.btn-edit', function() {
                const judul = $(this).data('judul');
                const existingLink = $(this).data('link'); // Ambil link dari data-link attribute

                $('#upload-judul').val(judul);
                $('#link').val(existingLink); // Isi input link dengan link yang sudah ada
                $('#modal-upload-title').text('Edit Link Publikasi'); // Ubah judul modal

                // Tampilkan modal upload/edit dengan efek fade-in
                $('#modal-upload').removeClass('hidden').addClass('flex');
                setTimeout(() => {
                    $('#modal-upload > div').removeClass('scale-0 opacity-0').addClass(
                        'scale-100 opacity-100');
                }, 50);
            });

            // Event klik untuk menutup modal upload/edit (tombol Batal dan tombol X atas)
            $('#close-upload, #close-upload-top').click(() => {
                $('#modal-upload > div').removeClass('scale-100 opacity-100').addClass('scale-0 opacity-0');
                setTimeout(() => {
                    $('#modal-upload').removeClass('flex').addClass('hidden');
                }, 300); // Sesuaikan dengan durasi transisi
            });

            // Submit form upload/edit link
            $('#form-upload-link').submit(function(e) {
                e.preventDefault(); // Mencegah form submit secara default
                const link = $('#link').val();
                const judul = $('#upload-judul').val();

                // Validasi sederhana untuk format URL
                if (!link || !/^https?:\/\//i.test(link)) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Link tidak valid',
                        text: 'Harap masukkan URL yang valid (diawali http:// atau https://).',
                    });
                    return;
                }

                $('#submit-btn').prop('disabled', true).text(
                    'Mengunggah...'); // Nonaktifkan tombol saat proses

                $.post("{{ route('siaga.pst.upload') }}", {
                    _token: '{{ csrf_token() }}', // Token CSRF Laravel
                    judul_publikasi: judul,
                    link: link
                }).done(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Link berhasil diupload!',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Tutup modal dengan transisi
                    $('#modal-upload > div').removeClass('scale-100 opacity-100').addClass(
                        'scale-0 opacity-0');
                    setTimeout(() => {
                        $('#modal-upload').removeClass('flex').addClass('hidden');
                    }, 300);

                    $('#pst-siaga-table').DataTable().ajax.reload(null,
                        false); // Refresh DataTables
                }).fail((jqXHR, textStatus, errorThrown) => {
                    console.error("Gagal upload link:", textStatus, errorThrown, jqXHR
                        .responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat upload link. (Lihat konsol browser untuk info lebih lanjut)',
                    });
                }).always(() => {
                    $('#submit-btn').prop('disabled', false).text(
                        'Kirim'); // Aktifkan kembali tombol
                });

            });
        });
    </script>
@endpush
