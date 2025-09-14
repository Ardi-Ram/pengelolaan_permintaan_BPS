@extends('layouts.admin')

@section('content')
    <div class="max-w-full bg-white m-5 border border-gray-300 rounded-lg">
        <h2 class="text-2xl font-bold p-5 border-b border-gray-300">Manajemen Perantara Permintaan</h2>

        @if (session('success'))
            <div class="mx-5 mt-4 p-3 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
        @endif

        <div class="m-5 border border-gray-300 rounded-lg">
            <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded m-4">+ Tambah Perantara</button>

            <div class="bg-white shadow overflow-x-auto rounded border-t border-gray-300">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-3">#</th>
                            <th class="p-3">Nama Perantara</th>
                            <th class="p-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($perantara as $i => $item)
                            <tr class="border-t">
                                <td class="p-3">{{ $i + 1 }}</td>
                                <td class="p-3">{{ $item->nama_perantara }}</td>
                                <td class="p-3">
                                    <button onclick="openModal(@json($item))"
                                        class="text-yellow-600 hover:underline">Edit</button>

                                    <form action="{{ route('perantara.destroy', $item->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-600 hover:underline ml-2">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        @if ($perantara->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center p-4 text-gray-500">Belum ada data perantara</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah/Edit -->
    <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-md p-6 rounded shadow">
            <h2 id="modalTitle" class="text-xl font-semibold mb-4">Tambah Perantara</h2>
            <form id="modalForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <div class="mb-4">
                    <label class="block mb-1">Nama Perantara</label>
                    <input type="text" name="nama_perantara" id="nama_perantara" class="w-full border px-3 py-2 rounded"
                        required>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal(data = null) {
            const modal = document.getElementById('modal');
            const form = document.getElementById('modalForm');
            const title = document.getElementById('modalTitle');
            const methodInput = document.getElementById('formMethod');
            const namaInput = document.getElementById('nama_perantara');

            if (data) {
                title.textContent = 'Edit Perantara';
                namaInput.value = data.nama_perantara;
                form.action = `/perantara/${data.id}`;
                methodInput.value = 'PUT';
            } else {
                title.textContent = 'Tambah Perantara';
                namaInput.value = '';
                form.action = "{{ route('perantara.store') }}";
                methodInput.value = 'POST';
            }

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }
    </script>
@endsection
