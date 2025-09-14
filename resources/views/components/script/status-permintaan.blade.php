   <script>
       function salinKode(elementId, button) {
           const text = document.getElementById(elementId).innerText;

           navigator.clipboard.writeText(text).then(function() {
               // Ganti ikon ke centang
               const icon = button.querySelector('span');
               const originalIcon = icon.innerText;
               icon.innerText = 'check_circle';

               // Kembalikan ke ikon semula setelah 1.5 detik
               setTimeout(() => {
                   icon.innerText = originalIcon;
               }, 1500);
           }).catch(function(err) {
               console.error('Gagal menyalin:', err);
           });
       }
   </script>

   <script>
       $(document).ready(function() {
           // Ambil nilai search dari query param URL
           const urlParams = new URLSearchParams(window.location.search);
           const initialSearch = urlParams.get('search') || '';

           const table = $('#status-table').DataTable({
               processing: true,
               serverSide: true,
               ajax: {
                   url: "{{ route('permintaan.getStatusData') }}",
                   data: function(d) {
                       d.kategori = $('#filter-kategori').val();
                       d.status = $('#filter-status').val();
                       d.pengolah = $('#filter-pengolah').val();

                       // Kirim nilai search dari URL ke server sebagai search[value]
                       if (initialSearch) {
                           d.search = {
                               value: initialSearch
                           };
                       }
                   }
               },
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
                       name: 'kategori'

                   },
                   {
                       data: 'status',
                       name: 'status'
                   },
                   {
                       data: 'pengolah',
                       name: 'pengolah',
                       orderable: false,
                       searchable: true,
                   },

                   {
                       data: 'kode_transaksi',
                       name: 'kode_transaksi'
                   },
                   {
                       data: 'action',
                       name: 'action',
                       orderable: false,
                       searchable: false
                   },
               ],
               createdRow: function(row, data, dataIndex) {
                   $(row).addClass(
                       'border-b border-gray-200 bg-white shadow-sm rounded-md');
                   $('td', row).addClass('px-4 py-3 text-sm text-gray-700');
               },

               dom: 'lftrip',
               language: {
                   lengthMenu: "_MENU_",
                   search: "",
                   searchPlaceholder: "Cari kode atau nama...",
               },
               initComplete: function() {
                   $('#custom-search').html($('#status-table_filter').contents());
                   $('#status-table_filter').remove();

                   $('#custom-length').html($('#status-table_length').contents());
                   $('#status-table_length').remove();

                   $('#custom-search input[type="search"]').addClass(
                       'border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring focus:border-blue-400'
                   ).attr('placeholder', 'Cari...');

                   $('#custom-length select').addClass(
                       'border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring focus:border-blue-400 w-16'
                   );
               }

           });

           $('#filter-kategori, #filter-status,#filter-pengolah').on('change', function() {
               table.ajax.reload();
           });

       });
   </script>

   <script>
       $(document).on('click', '.btn-batal-penugasan', function() {
           let id = $(this).data('id');

           if (confirm('Apakah Anda yakin ingin membatalkan penugasan ini?')) {
               $.ajax({
                   url: '/permintaan-data/' + id + '/batal-penugasan',
                   method: 'POST',
                   data: {
                       _token: '{{ csrf_token() }}'
                   },
                   success: function(response) {
                       if (response.success) {
                           alert(response.message);
                           $('#statusDataTable').DataTable().ajax.reload();
                           // Reload juga datatable penugasan supaya data yang batal penugasan muncul kembali
                           if ($.fn.DataTable.isDataTable('#penugasanTable')) {
                               $('#penugasanTable').DataTable().ajax.reload();
                           }
                       } else {
                           alert(response.message);
                       }
                   },
                   error: function() {
                       alert('Terjadi kesalahan saat membatalkan penugasan.');
                   }
               });
           }
       });
   </script>
