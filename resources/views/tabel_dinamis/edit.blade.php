@extends('layouts.app')

@section('title', 'Edit Tabel Statistik')

@section('content')
    <div class="w-full px-6 py-10 bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-100 min-h-screen">
        <div class="w-full bg-white p-8 rounded-2xl shadow-md max-w-7xl mx-auto">
            <h1 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-500">edit</span>
                Edit Tabel Statistik
            </h1>

            @if (session('success'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('tabel-dinamis.update', $tabel->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul', $tabel->judul) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring focus:ring-indigo-300"
                        required>
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring focus:ring-indigo-300" required>{{ old('deskripsi', $tabel->deskripsi) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori_id" id="kategori_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring focus:ring-indigo-300"
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ old('kategori_id', $tabel->kategori_id) == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-1">Subjek</label>
                        <select name="subject_id" id="subject_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring focus:ring-indigo-300"
                            required>
                            <option value="">-- Pilih Subjek --</option>
                            @foreach ($subjectList as $sub)
                                <option value="{{ $sub->id }}"
                                    {{ old('subject_id', $tabel->subject_id) == $sub->id ? 'selected' : '' }}>
                                    {{ $sub->nama_subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline (Opsional)</label>
                    <input type="date" name="deadline" id="deadline" value="{{ old('deadline', $tabel->deadline) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:ring focus:ring-indigo-300">
                </div>

                <div class="flex justify-end mt-6">
                    <a href="{{ route('tabel-dinamis.penugasan') }}"
                        class="mr-3 inline-block bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded shadow-sm">
                        Batal
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- JS AJAX untuk load subjek --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kategoriSelect = document.getElementById('kategori_id');
            const subjectSelect = document.getElementById('subject_id');

            kategoriSelect.addEventListener('change', function() {
                const kategoriId = this.value;

                subjectSelect.innerHTML = '<option value="">Memuat...</option>';

                fetch('{{ route('ajax.get-subjects') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            kategori_id: kategoriId
                        })
                    })
                    .then(res => res.json())
                    .then(subjects => {
                        subjectSelect.innerHTML = '<option value="">-- Pilih Subjek --</option>';
                        subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.nama_subject;
                            subjectSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        subjectSelect.innerHTML = '<option value="">Gagal memuat subjek</option>';
                        console.error(error);
                    });
            });
        });
    </script>
@endsection
