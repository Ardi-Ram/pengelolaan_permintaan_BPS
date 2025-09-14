<script>
    const subjectMap = @json(
        $kategori->mapWithKeys(function ($item) {
            return [
                $item->id => $item->subjects->map(function ($s) {
                    return ['id' => $s->id, 'nama' => $s->nama_subject];
                }),
            ];
        }));

    let index = 1;

    function updateSubjectOptions(kategoriId, subjectSelect) {
        subjectSelect.innerHTML = '<option value="">-- Pilih Subject --</option>';
        if (subjectMap[kategoriId]) {
            subjectMap[kategoriId].forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.nama;
                subjectSelect.appendChild(option);
            });
        }
    }

    // 🧠 Binding untuk field pertama (index 0)
    document.addEventListener('DOMContentLoaded', function() {
        const kategoriDropdown = document.getElementById('kategori-0');
        const subjectSelect = document.getElementById('subject-0');
        kategoriDropdown.addEventListener('change', function() {
            updateSubjectOptions(this.value, subjectSelect);
        });
    });

    // ➕ Tombol Tambah Form
    document.getElementById('add-form').addEventListener('click', () => {
        const wrapper = document.getElementById('form-wrapper');
        const newItem = document.createElement('div');
        newItem.classList.add('border', 'border-gray-200', 'p-6', 'rounded-2xl', 'bg-gray-50');
        newItem.id = `form-item-${index}`;

        newItem.innerHTML = `
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">title</span> Judul Tabel
                </label>
                <input type="text" name="data[${index}][judul]" class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">description</span> Deskripsi
                </label>
                <textarea name="data[${index}][deskripsi]" rows="3" class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">category</span> Kategori
                </label>
                <select name="data[${index}][kategori_id]" id="kategori-${index}" data-index="${index}"
                    class="kategori-dropdown w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}">{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">badge</span> Subject
                </label>
                <select name="data[${index}][subject_id]" id="subject-${index}"
                    class="subject-dropdown w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200" required>
                    <option value="">-- Pilih Subject --</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <span class="material-symbols-outlined text-gray-600 text-[18px] mr-1 align-middle">calendar_today</span> Deadline (Opsional)
                </label>
                <input type="date" name="data[${index}][deadline]" class="w-full rounded-lg border border-gray-300 bg-white p-2.5 focus:ring focus:ring-gray-200">
            </div>
        `;

        wrapper.appendChild(newItem);

        // 🧠 Pasang listener setelah elemen dimasukkan ke DOM
        const kategoriSelect = document.getElementById(`kategori-${index}`);
        const subjectSelect = document.getElementById(`subject-${index}`);

        kategoriSelect.addEventListener('change', function() {
            updateSubjectOptions(this.value, subjectSelect);
        });

        index++;
    });
</script>
