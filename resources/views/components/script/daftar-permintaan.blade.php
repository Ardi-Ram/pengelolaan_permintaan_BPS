       <script>
           function openDeskripsiModal(deskripsi) {
               document.getElementById('deskripsiContent').textContent = deskripsi;
               document.getElementById('deskripsiModal').classList.remove('hidden');
           }

           function closeDeskripsiModal() {
               document.getElementById('deskripsiModal').classList.add('hidden');
           }
       </script>
       <script>
           function openUploadModal(id) {
               const form = document.getElementById('uploadForm');
               form.action = `/pengolah/upload/${id}`; // atau sesuaikan route-nya
               document.getElementById('uploadModal').classList.remove('hidden');
           }

           function closeUploadModal() {
               document.getElementById('uploadModal').classList.add('hidden');
               document.getElementById('uploadForm').reset();
           }
       </script>


       <script>
           function openRejectModal(id) {
               const form = document.getElementById('rejectForm');
               form.action = "{{ url('/pengolah/reject') }}/" + id;
               document.getElementById('rejectModal').classList.remove('hidden');
           }

           function closeRejectModal() {
               document.getElementById('rejectModal').classList.add('hidden');
           }
       </script>

       <script>
           $(document).ready(function() {
               const table = $('#permintaan-table').DataTable({
                   processing: true,
                   serverSide: true,
                   ajax: {
                       url: "{{ route('pengolah.permintaan.data') }}",
                       type: "GET",
                       data: function(d) {
                           d.kategori = $('#filter-kategori').val(); // kirim kategori ke backend
                       }
                   },
                   columns: [{
                           data: 'DT_RowIndex',
                           className: 'text-gray-400 text-sm',
                           orderable: false,
                           searchable: false
                       },
                       {
                           data: 'kode_transaksi',
                           name: 'kode_transaksi'
                       },
                       {
                           data: 'judul_permintaan',
                           name: 'judul_permintaan'
                       },
                       {
                           data: 'kategori',
                           name: 'kategori',
                       },
                       {
                           data: 'petugas_pst',
                           name: 'petugas_pst'
                       },
                       {
                           data: 'created_at',
                           name: 'created_at'
                       },
                       {
                           data: 'aksi',
                           orderable: false,
                           searchable: false
                       }
                   ],
                   createdRow: function(row, data, dataIndex) {
                       $(row).addClass('bg-white shadow-sm rounded-md'); // Apply consistent row style
                   },
                   dom: '<"hidden"l><"hidden"f>t<"flex justify-between items-center mt-4"ip>', // hide bawaan filter dan lengthmenu
                   initComplete: function() {
                       // Move and style length menu
                       $('#custom-controls').prepend($('.dataTables_length select').addClass(
                           'border border-gray-300 rounded-md px-6 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                       ));

                       // Move and style search box
                       $('#custom-controls').append($('.dataTables_filter input').addClass(
                           'border border-gray-300 px-3 py-2 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-400'
                       ));

                       // Trigger reload saat kategori berubah
                       $('#filter-kategori').on('change', function() {
                           table.ajax.reload();
                       });
                   },
                   language: {
                       search: "",
                       searchPlaceholder: "Cari permintaan...",
                       lengthMenu: "Tampilkan _MENU_ entri",
                       emptyTable: `
                                    <div class="text-center py-8">
                                        <span class="material-symbols-outlined text-6xl text-gray-400 mb-4">inbox</span>
                                        <p class="text-gray-600 text-lg font-semibold">Belum ada permintaan data untuk ditampilkan.</p>
                                        <p class="text-gray-500 text-sm mt-1">Coba sesuaikan filter atau tunggu penugasan baru.</p>
                                    </div>
                                `,
                       paginate: {
                           previous: "Sebelumnya",
                           next: "Berikutnya"
                       }
                   },
                   drawCallback: function() {
                       $('.dataTables_paginate').addClass('mt-4 flex justify-center gap-1');
                       $('.dataTables_paginate a').addClass(
                           'px-3 py-1 rounded border text-sm text-blue-600 hover:bg-blue-100 transition'
                       );
                       $('.dataTables_paginate .current').addClass(
                           'bg-blue-500 text-white border-blue-500');
                   }
               });
           });
       </script>
