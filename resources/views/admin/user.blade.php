@extends('layouts.admin')
@section('content')
    <div class="m-5 bg-white border border-gray-300 rounded-lg shadow-sm overflow-hidden">
        <div class="flex justify-between items-center p-6 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-800">Manajemen User</h1>
            <a href="{{ route('tambah.user') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Tambah User
            </a>
        </div>

        @if (session('success'))
            <div class="mx-6 mt-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-sm"
                role="alert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-5">
            <div class="overflow-x-auto border border-gray-200 py-3 rounded-lg">
                <table id="users-table" class="min-w-full bg-white ">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  border-y border-gray-300">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-y border-gray-300">
                                Nama
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-y border-gray-300">
                                Email
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-y border-gray-300">
                                Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-y border-gray-300">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 ">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-gray-50 transition duration-150 border-b border-gray-300">
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-b border-gray-300text-gray-500">
                                    {{ $index + 1 }}</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-b border-gray-300">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-b border-gray-300">
                                    {{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm border-b border-gray-300">
                                    @if ($user->roles->isNotEmpty())
                                        @php
                                            $role = $user->roles->first()->name;
                                            $roleClasses = match ($role) {
                                                'petugas_pst' => 'bg-green-100 text-green-800',
                                                'pengolah_data' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp

                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $roleClasses }}">
                                            {{ ucfirst(str_replace('_', ' ', $role)) }}
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            Tidak ada role
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border-b border-gray-300">
                                    <div class="flex space-x-4">
                                        <a href="{{ route('edit.user', $user->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 flex items-center group">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span class="group-hover:underline">Edit</span>
                                        </a>
                                        <form action="{{ route('delete.user', $user->id) }}" method="POST"
                                            class="inline-block "
                                            onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 flex items-center group">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                <span class="group-hover:underline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5"
                                    class="px-6 py-12 text-center text-gray-500 bg-gray-50 border-b border-gray-300">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-3"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                        <p class="text-base">Belum ada data user</p>
                                        <p class="text-sm text-gray-400 mt-1">Silahkan tambahkan user baru</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let table = $('#users-table').DataTable({
                    "dom": '<"flex justify-end items-center mb-4"f>rt<"flex justify-between items-center mt-4 mx-2"ip>',
                    "language": {
                        "search": "",
                        "paginate": {
                            "next": "›",
                            "previous": "‹"
                        },
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                        "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                        "zeroRecords": "Tidak ada data yang cocok",
                        "emptyTable": "Tidak ada data tersedia"
                    },
                    "pagingType": "simple_numbers",
                    "lengthChange": false,
                    "pageLength": 10,
                    "drawCallback": function() {
                        // Set container pagination jadi flex dan beri jarak (Tailwind)
                        $('.dataTables_paginate').addClass('flex space-x-2 mx-2');

                        // Styling tombol pagination (Tailwind classes)
                        $('.paginate_button').each(function() {
                            $(this).addClass('px-3 py-1 rounded hover:bg-gray-100');

                            // Tailwind: tombol default
                            $(this).removeClass('bg-blue-100 text-blue-800 pointer-events-none');

                            // Jika tombol aktif, ganti style
                            if ($(this).hasClass('current')) {
                                $(this).addClass('bg-blue-100 text-blue-800 pointer-events-none');
                            }
                        });
                    }
                });

                // Styling search input (boleh tetap)
                const $searchLabel = $('.dataTables_filter label');
                const $searchInput = $searchLabel.find('input');

                const wrapper = $(`
        <div class="relative w-64 mx-3">
            <span class="material-icons absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-base pointer-events-none">
                search
            </span>
        </div>
    `);

                $searchInput
                    .attr('placeholder', 'Cari user...')
                    .addClass(
                    'pr-10 py-2 border rounded-md w-full focus:outline-none focus:ring-2 focus:ring-blue-500');

                wrapper.append($searchInput);
                $searchLabel.empty().append(wrapper);
            });
        </script>
    @endpush
@endsection
