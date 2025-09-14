@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-gray-50 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kategori & Subject</h1>
                            <p class="text-sm text-gray-600 mt-1">Kelola kategori dan subject pembelajaran</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="bg-blue-50 rounded-full p-3">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Alert -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-green-800 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <!-- Action Bar -->
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600">Total Kategori: <span
                                    class="font-semibold text-gray-900">{{ $categories->count() }}</span></span>
                        </div>
                        <button onclick="openModal()"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Kategori
                        </button>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">
                                    #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kategori & Subject</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($categories as $i => $category)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 align-top">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <span class="text-blue-600 font-medium text-sm">{{ $i + 1 }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        <!-- Category Name -->
                                        <div class="flex items-center mb-3">
                                            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg p-2 mr-3">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">
                                                    {{ $category->nama_kategori }}</h3>
                                                <p class="text-sm text-gray-500">{{ $category->subjects->count() }} subject
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Subjects List -->
                                        @if ($category->subjects->count())
                                            <div class="ml-11 space-y-2">
                                                @foreach ($category->subjects as $subject)
                                                    <div
                                                        class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-2 border border-gray-200">
                                                        <div class="flex items-center">
                                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-3"></div>
                                                            <span
                                                                class="text-sm text-gray-700 font-medium">{{ $subject->nama_subject }}</span>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <button onclick="openSubjectModal({{ $subject }})"
                                                                class="text-blue-600 hover:text-blue-800 text-xs font-medium transition-colors duration-150">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        stroke-width="2"
                                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                                    </path>
                                                                </svg>
                                                            </button>
                                                            <form action="{{ route('subject.destroy', $subject->id) }}"
                                                                method="POST" class="inline">
                                                                @csrf @method('DELETE')
                                                                <button onclick="return confirm('Hapus subject ini?')"
                                                                    class="text-red-600 hover:text-red-800 text-xs font-medium transition-colors duration-150">
                                                                    <svg class="w-4 h-4" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                        </path>
                                                                    </svg>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="ml-11 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                                                <p class="text-gray-500 italic text-sm flex items-center">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.728-.833-2.498 0L4.316 16.5c-.77.833.192 2.5 1.732 2.5z">
                                                        </path>
                                                    </svg>
                                                    Belum ada subject
                                                </p>
                                            </div>
                                        @endif

                                        <!-- Add Subject Form -->
                                        <div class="ml-11 mt-3">
                                            <form action="{{ route('subject.store', $category->id) }}" method="POST"
                                                class="flex items-center gap-2">
                                                @csrf
                                                <div class="flex-1">
                                                    <input type="text" name="nama_subject"
                                                        placeholder="Tambah subject baru..."
                                                        class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                                                        required>
                                                </div>
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Tambah
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm align-top">
                                        <div class="flex flex-col space-y-2">
                                            <button onclick="openModal({{ $category }})"
                                                class="inline-flex items-center px-3 py-1.5 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-lg text-sm font-medium transition-colors duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('categories.destroy', $category->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="inline-flex items-center px-3 py-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg text-sm font-medium transition-colors duration-150 w-full">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            @if ($categories->isEmpty())
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="bg-gray-100 rounded-full p-4 mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                    </path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada kategori</h3>
                                            <p class="text-gray-500 mb-4">Mulai dengan menambahkan kategori pertama</p>
                                            <button onclick="openModal()"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Tambah Kategori
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Kategori -->
    <div id="modal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-2xl border border-gray-200 transform transition-all">
            <div class="flex items-center justify-between mb-6">
                <h2 id="modalTitle" class="text-xl font-bold text-gray-900">Tambah Kategori</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="modalForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" id="nama_kategori"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                        placeholder="Masukkan nama kategori" required>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-150">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-150">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Subject -->
    <div id="subjectModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md p-6 rounded-xl shadow-2xl border border-gray-200 transform transition-all">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">Edit Subject</h2>
                <button onclick="closeSubjectModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="subjectModalForm" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Subject</label>
                    <input type="text" name="nama_subject" id="edit_nama_subject"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-150"
                        placeholder="Masukkan nama subject" required>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeSubjectModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors duration-150">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-150">
                        Simpan
                    </button>
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
            const namaInput = document.getElementById('nama_kategori');

            if (data) {
                title.textContent = 'Edit Kategori';
                namaInput.value = data.nama_kategori;
                form.action = `/categories/${data.id}`;
                methodInput.value = 'PUT';
            } else {
                title.textContent = 'Tambah Kategori';
                namaInput.value = '';
                form.action = "{{ route('categories.store') }}";
                methodInput.value = 'POST';
            }

            modal.classList.remove('hidden');
            namaInput.focus();
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        function openSubjectModal(subject) {
            const modal = document.getElementById('subjectModal');
            const form = document.getElementById('subjectModalForm');
            const namaInput = document.getElementById('edit_nama_subject');

            namaInput.value = subject.nama_subject;
            form.action = `/categories/subject/${subject.id}`;
            modal.classList.remove('hidden');
            namaInput.focus();
        }

        function closeSubjectModal() {
            document.getElementById('subjectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modal');
            const subjectModal = document.getElementById('subjectModal');

            if (event.target === modal) {
                closeModal();
            }
            if (event.target === subjectModal) {
                closeSubjectModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
                closeSubjectModal();
            }
        });
    </script>
@endsection
