 {{-- Script --}}
 <script>
     const kategoriOptionsHtml = `{!! collect($kategoriData)->map(fn($k) => "<option value='{$k->id}'>{$k->nama_kategori}</option>")->join('') !!}`;

     const subjectMap = @json(
         $subjectData->groupBy('category_data_id')->map(function ($items) {
             return $items->map(fn($s) => ['id' => $s->id, 'nama' => $s->nama_subject]);
         }));

     document.getElementById('generate-data').addEventListener('click', function() {
         const jumlah = parseInt(document.getElementById('jumlah_data').value);
         const container = document.getElementById('data-inputs');
         container.innerHTML = '';

         if (isNaN(jumlah) || jumlah < 1) return;

         for (let i = 1; i <= jumlah; i++) {
             const section = document.createElement('div');
             section.className = 'bg-white p-6 rounded-2xl shadow-sm border border-gray-200';

             section.innerHTML = `
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">Data ${i}</h3>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Data :</label>
                                    <input type="text" name="nama_data[]" required class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring focus:ring-cyan-100">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori Data :</label>
                                    <select name="kategori_id[]" class="kategori-dropdown w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        ${kategoriOptionsHtml}
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Subjek :</label>
                                    <select name="subject_id[]" class="subject-dropdown w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50" required>
                                        <option value="">-- Pilih Subjek -- : </option>
                                    </select>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi :</label>
                                    <textarea name="deskripsi[]" rows="3" required class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring focus:ring-cyan-100"></textarea>
                                </div>

                                
                            `;

             container.appendChild(section);

             const kategoriSelect = section.querySelector(".kategori-dropdown");
             const subjectSelect = section.querySelector(".subject-dropdown");

             // Auto-isi subject saat pertama kali
             kategoriSelect.addEventListener('change', function() {
                 const kategoriId = this.value;
                 const subjects = subjectMap[kategoriId] || [];

                 subjectSelect.innerHTML = '<option value="">-- Pilih Subjek --</option>';
                 subjects.forEach(subject => {
                     const opt = document.createElement('option');
                     opt.value = subject.id;
                     opt.textContent = subject.nama;
                     subjectSelect.appendChild(opt);
                 });
             });

             // ⬇ Tambahkan ini agar langsung isi subjek default (jika kategori dipilih pertama)
             kategoriSelect.dispatchEvent(new Event('change'));
         }
     });
 </script>
