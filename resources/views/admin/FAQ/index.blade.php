@extends('layouts.admin')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Manajemen FAQ</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Kelola pertanyaan yang sering diajukan</p>
            </div>
            <a href="{{ route('faq.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Tambah FAQ
            </a>
        </div>

        <!-- Success Alert -->
        @if (session('success'))
            <div id="success-alert"
                class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">{{ session('success') }}</span>
                    <button onclick="document.getElementById('success-alert').remove()"
                        class="ml-auto text-green-500 hover:text-green-700">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $faqs->count() }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total FAQ</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $faqs->count() }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">FAQ Aktif</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ now()->format('M Y') }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Bulan Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div
            class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Card Header -->
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 sm:mb-0">Daftar FAQ</h3>
                    <div class="flex items-center space-x-3">
                        <!-- Search Input -->
                        <div class="relative">
                            <input type="text" id="table-search" placeholder="Cari FAQ..."
                                class="w-64 pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Export Button -->
                        <button onclick="exportTable()"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table id="faq-table" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-16">
                                No.
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Pertanyaan
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Jawaban
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider w-32">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="faq-tbody">
                        @forelse ($faqs as $index => $faq)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-sm font-medium rounded-full">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ Str::limit($faq->question, 80) }}
                                    </div>
                                    @if (strlen($faq->question) > 80)
                                        <button
                                            onclick="showFullText('{{ addslashes($faq->question) }}', 'Pertanyaan Lengkap')"
                                            class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mt-1">
                                            Lihat selengkapnya...
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-300 line-clamp-3">
                                        {!! Str::limit(strip_tags($faq->answer), 120) !!}
                                    </div>
                                    @if (strlen(strip_tags($faq->answer)) > 120)
                                        <button
                                            onclick="showFullText('{{ addslashes(strip_tags($faq->answer)) }}', 'Jawaban Lengkap')"
                                            class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mt-1">
                                            Lihat selengkapnya...
                                        </button>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('faq.edit', $faq) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                            title="Edit FAQ">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                        <button
                                            onclick="confirmDelete({{ $faq->id }}, '{{ addslashes($faq->question) }}')"
                                            class="inline-flex items-center px-3 py-1.5 bg-red-100 hover:bg-red-200 text-red-700 text-xs font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1"
                                            title="Hapus FAQ">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>

                                    <!-- Hidden Delete Form -->
                                    <form id="delete-form-{{ $faq->id }}" action="{{ route('faq.destroy', $faq) }}"
                                        method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum ada FAQ
                                        </h3>
                                        <p class="text-gray-500 dark:text-gray-400 mb-4">Mulai dengan menambahkan FAQ
                                            pertama Anda</p>
                                        <a href="{{ route('faq.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                            Tambah FAQ Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Info -->
            @if ($faqs->count() > 0)
                <div class="px-6 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700 dark:text-gray-300">
                            Menampilkan <span class="font-medium">{{ $faqs->count() }}</span> FAQ
                        </div>
                        <div class="flex items-center space-x-2">
                            <select id="entries-per-page"
                                class="text-sm border border-gray-300 dark:border-gray-600 rounded px-3 py-1 dark:bg-gray-700 dark:text-white">
                                <option value="10">10 per halaman</option>
                                <option value="25">25 per halaman</option>
                                <option value="50">50 per halaman</option>
                                <option value="-1">Semua</option>
                            </select>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center pb-3 border-b border-gray-200 dark:border-gray-600">
                    <div
                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div class="ml-4 text-left">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Konfirmasi Hapus</h3>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="mt-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                        Apakah Anda yakin ingin menghapus FAQ ini?
                    </p>
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-lg p-3">
                        <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Pertanyaan:</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1" id="questionToDelete"></p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end pt-4 space-x-3">
                    <button onclick="closeDeleteModal()"
                        class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200">
                        Batal
                    </button>
                    <button id="confirmDeleteBtn"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors duration-200">
                        Hapus FAQ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Text Modal -->
    <div id="fullTextModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-lg bg-white dark:bg-gray-800">
            <div class="mt-3">
                <div class="flex items-center justify-between pb-3 border-b border-gray-200 dark:border-gray-600">
                    <h3 id="fullTextTitle" class="text-lg font-medium text-gray-900 dark:text-white"></h3>
                    <button onclick="closeFullTextModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mt-4 max-h-96 overflow-y-auto">
                    <p id="fullTextContent" class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Custom scrollbar */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Dark mode scrollbar */
        .dark .overflow-x-auto::-webkit-scrollbar-track {
            background: #374151;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #6b7280;
        }

        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#faq-table').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "Semua"]
                ],
                order: [
                    [0, 'asc']
                ],
                columnDefs: [{
                    targets: [0, 3],
                    orderable: false,
                    searchable: false
                }],
                language: {
                    search: "",
                    searchPlaceholder: "Cari FAQ...",
                    lengthMenu: "Tampilkan _MENU_ entri per halaman",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ FAQ",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 FAQ",
                    infoFiltered: "(difilter dari _MAX_ total FAQ)",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "Berikut",
                        previous: "Sebelum"
                    },
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian Anda",
                    emptyTable: "Tidak ada data FAQ tersedia"
                },
                dom: 't<"flex items-center justify-between mt-4"<"text-sm text-gray-700"i><"flex space-x-2"p>>',
                drawCallback: function(settings) {
                    updateTableInfo();
                }
            });

            // Connect external search input
            $('#table-search').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Connect entries per page selector
            $('#entries-per-page').on('change', function() {
                table.page.len(parseInt(this.value)).draw();
            });

            // Auto-hide success alert
            setTimeout(function() {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.style.transition = 'opacity 0.5s ease-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000);

            function updateTableInfo() {
                const info = table.page.info();
                const infoElement = document.querySelector('.dataTables_info');
                if (infoElement && info.recordsDisplay > 0) {
                    infoElement.textContent =
                        `Menampilkan ${info.start + 1} sampai ${info.end} dari ${info.recordsDisplay} FAQ`;
                }
            }
        });

        // Delete confirmation functions
        let faqIdToDelete = null;

        function confirmDelete(faqId, question) {
            faqIdToDelete = faqId;
            document.getElementById('questionToDelete').textContent = question;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            faqIdToDelete = null;
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (faqIdToDelete) {
                document.getElementById('delete-form-' + faqIdToDelete).submit();
            }
        });

        // Full text modal functions
        function showFullText(content, title) {
            document.getElementById('fullTextTitle').textContent = title;
            document.getElementById('fullTextContent').textContent = content;
            document.getElementById('fullTextModal').classList.remove('hidden');
        }

        function closeFullTextModal() {
            document.getElementById('fullTextModal').classList.add('hidden');
        }

        // Export function
        function exportTable() {
            const table = $('#faq-table').DataTable();
            const data = table.data().toArray();

            if (data.length === 0) {
                alert('Tidak ada data untuk diekspor');
                return;
            }

            // Create CSV content
            let csv = 'No,Pertanyaan,Jawaban\n';
            data.forEach((row, index) => {
                const question = $(row[1]).text().replace(/"/g, '""');
                const answer = $(row[2]).text().replace(/"/g, '""');
                csv += `${index + 1},"${question}","${answer}"\n`;
            });

            // Download CSV
            const blob = new Blob([csv], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', `FAQ_Export_${new Date().toISOString().slice(0, 10)}.csv`);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Click outside modal to close
        window.onclick = function(event) {
            const deleteModal = document.getElementById('deleteModal');
            const fullTextModal = document.getElementById('fullTextModal');

            if (event.target === deleteModal) {
                closeDeleteModal();
            }
            if (event.target === fullTextModal) {
                closeFullTextModal();
            }
        }

        // Keyboard navigation for modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
                closeFullTextModal();
            }
        });

        // Loading state for buttons
        document.addEventListener('click', function(event) {
            if (event.target.matches('button:not(.no-loading)') || event.target.matches('a.btn')) {
                const element = event.target;
                const originalContent = element.innerHTML;

                // Add loading state
                element.disabled = true;
                element.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Loading...
        `;

                // Restore original state after delay
                setTimeout(() => {
                    element.disabled = false;
                    element.innerHTML = originalContent;
                }, 1000);
            }
        });

        // Smooth scroll to top when navigating
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Enhanced search with highlighting
        function highlightSearchTerms(text, searchTerm) {
            if (!searchTerm) return text;

            const regex = new RegExp(`(${searchTerm})`, 'gi');
            return text.replace(regex, '<mark class="bg-yellow-200 dark:bg-yellow-600">$1</mark>');
        }

        // Table row animation
        function animateTableRows() {
            const rows = document.querySelectorAll('#faq-tbody tr');
            rows.forEach((row, index) => {
                row.style.opacity = '0';
                row.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '1';
                    row.style.transform = 'translateY(0)';
                }, index * 100);
            });
        }

        // Initialize animations on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelectorAll('#faq-tbody tr').length > 0) {
                setTimeout(animateTableRows, 100);
            }
        });

        console.log('FAQ Management script loaded successfully');
    </script>
@endpush
