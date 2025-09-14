  {{-- Script --}}
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const btnInfo = document.getElementById('btn-info-aksi');
          const modal = document.getElementById('modal-info-aksi');
          const closeModal = document.getElementById('close-info-aksi');

          btnInfo.addEventListener('click', () => {
              modal.classList.remove('hidden');
          });

          closeModal.addEventListener('click', () => {
              modal.classList.add('hidden');
          });

          // Tutup modal saat klik di luar box
          modal.addEventListener('click', (e) => {
              if (e.target === modal) {
                  modal.classList.add('hidden');
              }
          });
      });
  </script>

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
          $('#permintaan-table').on('draw.dt', initModalAlasan);
      });
  </script>
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
      document.addEventListener('DOMContentLoaded', function() {
          $(document).on('click', '.btn-penugasan', function() {
              const id = $(this).data('id');
              $('#permintaan_id').val(id);
              $('#modal-penugasan').removeClass('hidden');
          });

          $('#close-modal').on('click', function() {
              $('#modal-penugasan').addClass('hidden');
          });

          const table = $('#permintaan-table').DataTable({
              processing: true,
              serverSide: true,
              ajax: {
                  url: "{{ route('permintaan.getData') }}",
                  data: function(d) {
                      d.kategori = $('#filter-kategori').val(); // kirim kategori filter
                      d.status = $('#filter-status').val();
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
                  }, {
                      data: 'status',
                      name: 'status'
                  },
                  {
                      data: 'created_at',
                      name: 'created_at'
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
              dom: '<"hidden"l><"hidden"f>t<"flex justify-between items-center mt-4"ip>', // hide bawaan filter dan lengthmenu
              initComplete: function() {
                  // Ambil elemen length & search
                  const lengthSelect = $('.dataTables_length select');
                  const searchInput = $('.dataTables_filter input');

                  // Hapus teks label default supaya tidak dobel
                  $('.dataTables_length label').contents().filter(function() {
                      return this.nodeType === 3; // text node
                  }).remove();
                  $('.dataTables_filter label').contents().filter(function() {
                      return this.nodeType === 3;
                  }).remove();

                  // Tambahkan style Tailwind
                  lengthSelect.addClass(
                      'border border-gray-300 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400'
                  );
                  searchInput.addClass(
                      'border border-gray-300 px-2 py-1 rounded text-xs focus:outline-none focus:ring-1 focus:ring-blue-400'
                  );

                  // (Opsional) Tambahkan placeholder
                  searchInput.attr('placeholder', 'Cari...');

                  // Pindahkan ke tempat yang sudah kamu siapkan
                  $('#custom-length').append(lengthSelect);
                  $('#custom-search').append(searchInput);
              },

              language: {
                  search: "",
                  searchPlaceholder: "Cari permintaan...",
                  lengthMenu: "Tampilkan _MENU_ entri",
                  paginate: {
                      previous: "Sebelumnya",
                      next: "Berikutnya"
                  }
              }
          });


          // Trigger reload saat filter diubah
          $('#filter-kategori, #filter-status').on('change', function() {
              $('#permintaan-table').DataTable().ajax.reload();
          });
          $(document).ready(function() {
              $('.select2').select2({
                  width: '100%', // pastikan full width
                  placeholder: "Pilih Pengolah", // optional placeholder
                  allowClear: true // optional: bisa hapus pilihan
              });
          });

      });
  </script>
