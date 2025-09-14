   {{-- Script --}}
   <script>
       function openModal(alasan, pengolah) {
           const modal = document.getElementById('modal-alasan');
           document.getElementById('modal-alasan-text').textContent = alasan || '-';
           document.getElementById('modal-nama-pengolah').textContent = pengolah || '-';

           modal.classList.remove('hidden');
           modal.classList.add('flex');
       }

       function closeModal() {
           const modal = document.getElementById('modal-alasan');
           modal.classList.remove('flex');
           modal.classList.add('hidden');
       }

       function initModalAlasan() {
           document.querySelectorAll('.btn-alasan').forEach(button => {
               button.addEventListener('click', function() {
                   const alasan = this.dataset.alasan;
                   const pengolah = this.dataset.pengolah;
                   openModal(alasan, pengolah);
               });
           });

           document.getElementById('btn-close-modal').addEventListener('click', closeModal);
           document.getElementById('btn-close-modal-footer').addEventListener('click', closeModal);
       }

       document.addEventListener('DOMContentLoaded', function() {
           initModalAlasan();
           $('#tabel-dinamis-table').on('draw.dt', initModalAlasan);
           // Atau ganti ID sesuai tabel kamu
       });
   </script>

   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const table = $('#tabel-dinamis-table').DataTable({
               processing: true,
               serverSide: true,
               ajax: {
                   url: "{{ route('tabel-dinamis.penugasan.data') }}",
                   data: function(d) {
                       d.kategori_id = $('#filter-kategori').val();
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
                       data: 'aksi',
                       name: 'aksi',
                       orderable: false,
                       searchable: false
                   }
               ],
               createdRow: function(row) {
                   $(row).addClass('bg-white shadow-sm rounded-md');
               },
               // Hapus l (length menu) dan f (search) dari DOM
               dom: 't<"flex justify-between items-center mt-4"ip>',
               language: {
                   paginate: {
                       previous: "Sebelumnya",
                       next: "Berikutnya"
                   }
               }
           });

           $('#filter-kategori').change(() => table.ajax.reload());

           $(document).on('click', '.assign-btn', function() {
               $('#tabel_id').val($(this).data('id'));
               $('#modal-penugasan').removeClass('hidden');
           });

           $('#close-modal').click(() => $('#modal-penugasan').addClass('hidden'));

           $('#form-penugasan').submit(function(e) {
               e.preventDefault();
               const id = $('#tabel_id').val();
               const data = $(this).serialize();

               $.post(`/penugasan-tabel-dinamis/assign/${id}`, data, function(res) {
                   if (res.success) {
                       $('#modal-penugasan').addClass('hidden');
                       table.ajax.reload();
                       toastr.success(res.message || 'Pengolah berhasil ditugaskan!');
                   }
               }).fail(function(xhr) {
                   if (xhr.responseJSON?.errors) {
                       let errorMessages = Object.values(xhr.responseJSON.errors).flat().join(
                           '<br>');
                       toastr.error(errorMessages);
                   } else {
                       toastr.error('Terjadi kesalahan saat menyimpan data.');
                   }
               });
           });
       });
   </script>
