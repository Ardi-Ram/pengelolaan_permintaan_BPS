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
