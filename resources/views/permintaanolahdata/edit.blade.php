@extends('layouts.petugas')

@section('title', 'Edit Permintaan Data')

@section('content')
    <div class="max-w-6xl mx-auto my-10 bg-white rounded shadow border border-gray-300">
        <h2 class="text-2xl font-bold text-gray-800 border-b px-6 py-4">Edit Permintaan Data</h2>

        @if (session('error'))
            <div class="bg-red-100 text-red-800 px-4 py-2 rounded mx-6 mt-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="p-6">
            <form action="{{ route('permintaan.update', $permintaan->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Info Pemilik --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pemilik</label>
                        <input type="text" name="nama" value="{{ old('nama', $permintaan->pemilikData->nama_pemilik) }}"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instansi</label>
                        <input type="text" name="instansi"
                            value="{{ old('instansi', $permintaan->pemilikData->instansi) }}"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $permintaan->pemilikData->email) }}"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No WhatsApp</label>
                        <input type="text" name="no_wa" value="{{ old('no_wa', $permintaan->pemilikData->no_wa) }}"
                            class="w-full border px-3 py-2 rounded" required>
                    </div>
                </div>

                {{-- Info Permintaan --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Judul Permintaan</label>
                    <input type="text" name="judul_permintaan"
                        value="{{ old('judul_permintaan', $permintaan->judul_permintaan) }}"
                        class="w-full border px-3 py-2 rounded" required>
                </div>

                {{-- Kategori --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select id="kategori" name="kategori_id" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategoriData as $kategori)
                            <option value="{{ $kategori->id }}"
                                {{ old('kategori_id', $permintaan->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama_kategori }}
                            </option>
                        @endforeach
                    </select>

                </div>


                {{-- Subject --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                    <select id="subject" name="subject_id" class="form-control">
                        <option value="">-- Pilih Subject --</option>
                    </select>
                </div>


                {{-- Deskripsi --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full border px-3 py-2 rounded" required>{{ old('deskripsi', $permintaan->deskripsi) }}</textarea>
                </div>

                {{-- Tombol Submit --}}
                <div class="text-right">
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script dinamis subject --}}
    <script>
        const subjectMap = @json($subjectMap);
        const kategoriSelect = document.getElementById('kategori');
        const subjectSelect = document.getElementById('subject');
        const selectedSubjectId = "{{ old('subject_id', $permintaan->subject_id) }}";

        function loadSubjects(kategoriId) {
            subjectSelect.innerHTML = '<option value="">-- Pilih Subject --</option>';
            const subjects = subjectMap[String(kategoriId)] || [];

            if (subjects.length === 0) {
                subjectSelect.innerHTML = '<option value="">-- Tidak ada subject untuk kategori ini --</option>';
                return;
            }

            subjects.forEach(subject => {
                const opt = document.createElement('option');
                opt.value = subject.id;
                opt.textContent = subject.nama;
                if (String(subject.id) === selectedSubjectId) {
                    opt.selected = true;
                }
                subjectSelect.appendChild(opt);
            });
        }

        // load pertama sesuai kategori sekarang
        if (kategoriSelect.value) {
            loadSubjects(kategoriSelect.value);
        }

        // event ganti kategori
        kategoriSelect.addEventListener('change', e => {
            loadSubjects(e.target.value);
        });
    </script>


@endsection
