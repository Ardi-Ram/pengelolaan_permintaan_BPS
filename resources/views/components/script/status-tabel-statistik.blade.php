<script>
    $(document).on('click', '.btn-batalkan', function() {
        const id = $(this).data('id');
        const judul = $(this).data('judul');

        Swal.fire({
            title: 'Batalkan Penugasan?',
            html: `Yakin ingin membatalkan penugasan untuk tabel:<br><strong>${judul}</strong>?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#e3342f'
        }).then((result) => {
            if (result.isConfirmed) {
                $.post(`/tabel-dinamis/${id}/batalkan`, {
                    _token: '{{ csrf_token() }}'
                }, function(res) {
                    if (res.success) {
                        Swal.fire('Berhasil!', res.message, 'success');
                        $('#your-datatable-id').DataTable().ajax
                            .reload(); // ganti sesuai ID tabel
                    } else {
                        Swal.fire('Gagal', res.message || 'Terjadi kesalahan.', 'error');
                    }
                });
            }
        });
    });
</script>

<script>
    function closeEditModal() {
        document.getElementById('editPublishModal').classList.add('hidden');
    }

    $(document).on('click', '.btn-edit-publish', function() {
        const id = $(this).data('id');
        const link = $(this).data('link');

        $('#edit-id').val(id);
        $('#edit-link-publish').val(link);
        $('#editPublishModal').removeClass('hidden');
    });

    $('#formEditLinkPublish').on('submit', function(e) {
        e.preventDefault();
        const id = $('#edit-id').val();
        const link = $('#edit-link-publish').val();

        $.ajax({
            url: `/tabel-dinamis/${id}/edit-link-publish`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                link_publish: link
            },
            success: function(res) {
                Swal.fire('Berhasil', 'Link publikasi berhasil diperbarui.', 'success');
                closeEditModal();
                $('#your-status-table-id').DataTable().ajax.reload(); // ganti dgn ID tabelmu
            },
            error: function() {
                Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
            }
        });
    });
</script>


{{-- Script --}}
<script>
    const table = $('#status-tabel-dinamis').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('tabel-dinamis.status.data') }}",
            data: function(d) {
                d.kategori_id = $('#filter-kategori').val();
                d.search_judul = $('#search-judul').val();
            }
        },
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
                data: 'deadline',
                name: 'deadline'
            },
            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'pengolah',
                name: 'pengolah'
            },
            {
                data: 'aksi',
                name: 'aksi',
                orderable: false,
                searchable: false
            },
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).addClass(
                'border-b border-gray-200  shadow-sm rounded-sm');
            $('td', row).addClass('px-4 py-3 text-sm text-gray-700');
        },
        dom: '<"hidden"l><"hidden"f>t<"flex justify-between items-center mt-4"ip>',
        language: {
            search: "",
            searchPlaceholder: "Cari data...",
            lengthMenu: " _MENU_ ",
            paginate: {
                previous: "Sebelumnya",
                next: "Berikutnya"
            }
        },
        initComplete: function() {
            $('#custom-search').html($('#status-tabel-dinamis_filter').contents());
            $('#status-tabel-dinamis_filter').remove();

            $('#custom-length').html($('#status-tabel-dinamis_length').contents());
            $('#status-tabel-dinamis_length').remove();

            $('#custom-search input[type="search"]').addClass(
                'border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring focus:border-blue-400'
            ).attr('placeholder', 'Cari...');

            $('#custom-length select').addClass(
                'border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring focus:border-blue-400 w-16'
            );
        }
    });
</script>
