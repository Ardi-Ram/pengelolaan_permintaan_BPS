@extends('layouts.pengolah')

@section('title', 'Import Data Publikasi')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6 px-4">
        <div class="container mx-auto max-w-4xl">
            {{-- Header --}}
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Import Data Publikasi</h1>
                <p class="text-gray-600">Upload file CSV untuk mengimpor data publikasi</p>
            </div>

            {{-- Messages --}}
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 rounded">
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            @elseif (session('error'))
                <div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 rounded">
                    <p class="text-red-800">{{ session('error') }}</p>
                    @if ($errors->any())
                        <ul class="mt-2 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endif

            {{-- Upload Section --}}
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="bg-blue-600 text-white px-4 py-3 rounded-t-lg">
                    <h2 class="font-semibold">Upload File CSV</h2>
                </div>

                <div class="p-4">
                    <div class="text-right mb-3">
                        <a href="{{ asset('contoh/contoh_tabel_air_bersih.csv') }}"
                            class="text-blue-600 text-sm hover:underline" download>
                            📥 Unduh Contoh CSV
                        </a>
                    </div>

                    <form action="{{ route('siaga.import.handle') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <input type="file" name="file" id="file" accept=".csv" required class="hidden"
                                onchange="updateFileName(this)">
                            <label for="file"
                                class="flex items-center justify-center w-full h-20 border-2 border-dashed border-gray-300 rounded cursor-pointer hover:border-gray-400">
                                <div class="text-center">
                                    <p class="text-gray-600" id="file-text">Klik untuk memilih file CSV</p>
                                    <p class="text-xs text-gray-400">Maksimal 10MB</p>
                                </div>
                            </label>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Upload & Preview
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Preview Section --}}
            @if (!empty($importedData))
                <div class="bg-white rounded-lg shadow">
                    <div class="bg-green-600 text-white px-4 py-3 rounded-t-lg">
                        <h3 class="font-semibold">Preview Data (<span id="total-rows">{{ count($importedData) }}</span>
                            baris)</h3>
                    </div>

                    <div class="p-4">
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded text-sm">
                            ⚠️ Periksa data di bawah ini. Anda dapat mengedit atau menghapus baris sebelum menyimpan.
                        </div>

                        <form id="save-form" action="{{ route('siaga.import.simpan') }}" method="POST">
                            @csrf

                            <div class="overflow-x-auto max-h-64 border rounded">
                                <table class="min-w-full text-sm" id="preview-table">
                                    <thead class="bg-gray-50 sticky top-0">
                                        <tr>
                                            @foreach (array_keys($importedData[0]) as $header)
                                                <th class="px-2 py-2 text-left font-medium text-gray-700 border-r">
                                                    {{ str_replace('_', ' ', $header) }}
                                                </th>
                                            @endforeach
                                            <th class="px-2 py-2 text-center font-medium text-gray-700">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($importedData as $i => $row)
                                            <tr id="row-{{ $i }}"
                                                class="{{ $i % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                                @foreach ($row as $key => $value)
                                                    @php
                                                        $normalizedKey = str_replace(' ', '_', strtolower(trim($key)));
                                                    @endphp
                                                    <td class="px-2 py-1 border-r">
                                                        <input type="text"
                                                            name="data[{{ $i }}][{{ $normalizedKey }}]"
                                                            value="{{ $value }}"
                                                            class="w-full px-1 py-1 text-xs border-0 bg-transparent focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-400 rounded" />
                                                    </td>
                                                @endforeach
                                                <td class="px-2 py-1 text-center">
                                                    <button type="button" data-row-id="row-{{ $i }}"
                                                        class="delete-row-btn text-red-600 hover:text-red-800">
                                                        🗑️
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-between items-center mt-4 pt-4 border-t">
                                <div class="flex items-center gap-4">
                                    <div class="text-sm text-gray-600">
                                        Total: <span class="font-bold"
                                            id="total-rows-footer">{{ count($importedData) }}</span> baris
                                    </div>
                                    <button type="button" id="add-row-btn"
                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                        + Tambah Baris
                                    </button>
                                </div>
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                    Simpan ke Database
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function updateFileName(input) {
                const fileText = document.getElementById('file-text');
                fileText.textContent = input.files.length > 0 ? input.files[0].name : 'Klik untuk memilih file CSV';
            }

            function updateRowCount() {
                const visibleRows = document.querySelectorAll('#preview-table tbody tr:not([style*="display: none"])').length;
                document.getElementById('total-rows').textContent = visibleRows;
                document.getElementById('total-rows-footer').textContent = visibleRows;
            }

            document.addEventListener('DOMContentLoaded', function() {
                let rowCounter = {{ count($importedData ?? []) }};
                const tableHeaders = @json(array_keys($importedData[0] ?? []));

                // Add new row handler
                document.getElementById('add-row-btn')?.addEventListener('click', function() {
                    const tbody = document.querySelector('#preview-table tbody');
                    if (!tbody) return;

                    const newRow = document.createElement('tr');
                    newRow.id = `row-${rowCounter}`;
                    newRow.className = rowCounter % 2 == 0 ? 'bg-white' : 'bg-gray-50';

                    let rowHtml = '';
                    tableHeaders.forEach(header => {
                        const normalizedKey = header.replace(' ', '_').toLowerCase();
                        rowHtml += `
                            <td class="px-2 py-1 border-r">
                                <input type="text" 
                                    name="data[${rowCounter}][${normalizedKey}]"
                                    value=""
                                    class="w-full px-1 py-1 text-xs border-0 bg-transparent focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-400 rounded" />
                            </td>
                        `;
                    });

                    rowHtml += `
                        <td class="px-2 py-1 text-center">
                            <button type="button" data-row-id="row-${rowCounter}"
                                class="delete-row-btn text-red-600 hover:text-red-800">
                                🗑️
                            </button>
                        </td>
                    `;

                    newRow.innerHTML = rowHtml;
                    tbody.appendChild(newRow);
                    rowCounter++;
                    updateRowCount();
                });

                document.addEventListener('click', function(event) {
                    const deleteButton = event.target.closest('.delete-row-btn');
                    if (deleteButton) {
                        const rowId = deleteButton.dataset.rowId;
                        const rowToDelete = document.getElementById(rowId);

                        Swal.fire({
                            title: 'Hapus baris?',
                            text: "Baris ini akan dihapus dari preview",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                rowToDelete.remove();
                                updateRowCount();
                                Swal.fire('Dihapus!', 'Baris telah dihapus.', 'success');
                            }
                        });
                    }
                });


                // Form submission handler
                document.querySelectorAll('form').forEach(form => {
                    form.addEventListener('submit', function(e) {
                        const fileInput = form.querySelector('input[type="file"]');
                        if (fileInput && !fileInput.files.length) {
                            e.preventDefault();
                            Swal.fire('Oops!', 'Silakan pilih file CSV terlebih dahulu.', 'warning');
                            return false;
                        }

                    });
                });
                const saveForm = document.getElementById('save-form');
                if (saveForm) {
                    saveForm.addEventListener('submit', function(e) {
                        const inputs = saveForm.querySelectorAll('input[type="text"]');
                        let emptyFound = false;

                        inputs.forEach(input => {
                            if (input.value.trim() === '') {
                                input.classList.add('border-red-500', 'bg-red-50');
                                emptyFound = true;
                            } else {
                                input.classList.remove('border-red-500', 'bg-red-50');
                            }
                        });

                        if (emptyFound) {
                            e.preventDefault(); // hentikan submit
                            Swal.fire('Validasi Gagal', 'Semua kolom wajib diisi sebelum menyimpan.', 'error');
                            return false;
                        }

                        // ✅ Baru disable tombol jika semua valid
                        const submitBtn = this.querySelector('button[type="submit"]');
                        if (submitBtn) {
                            submitBtn.disabled = true;
                            submitBtn.textContent = 'Memproses...';
                        }
                    });
                }



                @if (!empty($importedData))
                    updateRowCount();
                @else
                    // Initialize empty state
                    const addRowBtn = document.getElementById('add-row-btn');
                    if (addRowBtn) addRowBtn.style.display = 'none';
                @endif
            });
        </script>
    @endpush
@endsection
