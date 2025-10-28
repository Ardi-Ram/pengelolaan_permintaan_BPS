<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pengolah Data</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Tailwind (boleh tetap di head) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Icon Font -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jQuery (PASTIKAN cuma 1 dan di awal sebelum semua plugin JS lain) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="icon" href="{{ asset('images/bps-logo.png') }}" type="image/png">
    <style>
        /* CSS untuk menskalakan seluruh halaman menjadi 90% */
        html {
            font-size: 90%;
            /* Mengubah ukuran font dasar, yang akan menskalakan semua unit rem */
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 0.5rem !important;
        }

        /* Gaya dasar semua tombol pagination */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background-color: white;
            color: #374151 !important;
            /* gray-700 */
            border: 1px solid #e5e7eb;
            padding: 6px 12px;
            border-radius: 0.375rem;
            margin: 0 2px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        /* Hover tombol NON aktif */
        .dataTables_wrapper .dataTables_paginate .paginate_button:not(.current):hover {
            background-color: #e0f2fe !important;
            /* blue-100 */
            color: #1d4ed8 !important;
            /* blue-700 */
            border: 1px solid #93c5fd !important;
            /* blue-300 */
        }

        /* Tombol aktif */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #3b82f6 !important;
            /* blue-500 */
            color: #ffffff !important;
            border: 1px solid #3b82f6;
            font-weight: 600;
        }



        /* Perbaiki semua length menu DataTable di seluruh halaman */
        .dataTables_length select {
            padding-right: 1.5rem;
            /* ruang untuk panah */
            appearance: none;
            /* hilangkan panah default bawaan browser */
            background-image: url('data:image/svg+xml;charset=UTF-8,<svg fill="none" stroke="%23666" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>');
            background-repeat: no-repeat;
            background-position: right 0.4rem center;
            background-size: 1rem;
        }

        /* Optional: biar font & tampilan konsisten */
        .dataTables_length select {
            border: 1px solid #d1d5db;
            /* border-gray-300 */
            border-radius: 0.25rem;
            /* rounded */
            padding-top: 0.25rem;
            /* py-1 */
            padding-bottom: 0.25rem;
            padding-left: 0.5rem;
            /* px-2 */
            font-size: 0.75rem;
            /* text-xs */
            line-height: 1.25rem;
            /* optional, biar tinggi rapi */
        }



        .dataTables_filter input {
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        /* 1. Sembunyikan scrollbar secara default */
        .custom-scrollbar {
            scrollbar-width: none;
            /* Firefox */
            -ms-overflow-style: none;
            /* IE/Edge */
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        /* 2. Saat hover ke area nav, scrollbar muncul tipis */
        .custom-scrollbar:hover {
            scrollbar-width: thin;
        }

        .custom-scrollbar:hover::-webkit-scrollbar {
            width: 4px;
        }

        /* 3. Saat hover ke scrollbar itu sendiri, scrollbar tebal */
        .custom-scrollbar:hover::-webkit-scrollbar-thumb:hover {
            background-color: #a0aec0;
            /* gray-400 */
            width: 8px;
        }

        /* Tambahan: style dasar scrollbar */
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            /* gray-300 */
            border-radius: 6px;
        }
    </style>
</head>

<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-900 shadow-lg sticky top-0 self-start h-screen flex flex-col">

            <header class="p-4 flex justify-between items-center gap-x-2 mb-4  text-lg "> {{-- mb-4 sesuai referensi --}}
                <a class="flex items-center gap-x-2 font-semibold text-xl text-white" href="#">
                    {{-- text-black sesuai referensi --}}
                    <img src="{{ asset('images/bps-logo.png') }}" alt="Logo BPS" class="h-8 w-auto">
                    Pengolah Data {{-- Judul aplikasi Anda --}}
                </a>
            </header>

            <nav class="h-[calc(100vh-80px)] py-4 overflow-y-auto custom-scrollbar pr-2 mb-4">
                <ul class="space-y-2 text-white">

                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('pengolah.dashboard') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
           {{ request()->routeIs('pengolah.dashboard')
               ? 'bg-white/10 backdrop-blur-md text-white'
               : 'hover:bg-white/10 hover:backdrop-blur-sm text-gray-200' }}">
                            <span class="material-symbols-outlined">dashboard</span>
                            Dashboard
                        </a>
                    </li>

                    <!-- Permintaan Olah Data -->
                    <li class="mt-2 border-t border-blue-700 pt-2">
                        <div class="px-3 py-2 text-xs uppercase tracking-wide font-medium text-gray-300">
                            Permintaan Olah Data
                        </div>
                        <ul class="ml-2 space-y-1">
                            <li>
                                <a href="{{ route('pengolah.index') }}"
                                    class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                   {{ request()->routeIs('pengolah.index')
                       ? 'bg-white/10 backdrop-blur-md text-white'
                       : 'hover:bg-white/10 hover:backdrop-blur-sm text-gray-200' }}">
                                    <span class="material-symbols-outlined">assignment</span>
                                    Permintaan Ditugaskan
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Tabel Statistik -->
                    <li class="border-t border-blue-700 pt-2">
                        <div class="px-3 py-2 text-xs uppercase tracking-wide font-medium text-gray-300">
                            Tabel Statistik
                        </div>
                        <ul class="ml-2 space-y-1">
                            <li>
                                <a href="{{ route('tabeldinamis') }}"
                                    class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                   {{ request()->routeIs('tabeldinamis') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                                    <span class="material-symbols-outlined">list</span>
                                    Daftar Tugas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('tabeldinamis.upload.page') }}"
                                    class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                   {{ request()->routeIs('tabeldinamis.upload.page')
                       ? 'bg-blue-700 text-white'
                       : 'hover:bg-blue-800 text-gray-200' }}">
                                    <span class="material-symbols-outlined">upload</span>
                                    Upload Link
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Tabel Publikasi -->
                    <li class="border-t border-blue-700 pt-2">
                        <div class="px-3 py-2 text-xs uppercase tracking-wide font-medium text-gray-300">
                            Tabel Publikasi
                        </div>
                        <ul class="ml-2 space-y-1">
                            <li>
                                <a href="{{ route('siaga.import.form') }}"
                                    class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                   {{ request()->routeIs('siaga.import.form') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                                    <span class="material-symbols-outlined">upload_file</span>
                                    Import & Preview
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('siaga.penugasan') }}"
                                    class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                   {{ request()->routeIs('siaga.penugasan') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                                    <span class="material-symbols-outlined">person_add</span>
                                    Penugasan PST
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Direktori Data -->
                    <li class="border-t border-blue-700 pt-2">
                        <a href="{{ route('pengolah.direktori.view') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
           {{ request()->routeIs('pengolah.direktori.view') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                            <span class="material-symbols-outlined">folder_open</span>
                            Direktori Data
                        </a>
                    </li>

                    <!-- Logout -->
                    <li class="border-t border-blue-700 pt-2">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                    text-red-400 hover:bg-red-600/20">
                                <span class="material-symbols-outlined">logout</span>
                                Logout
                            </button>
                        </form>
                    </li>

                </ul>

            </nav>
        </aside>


        <main class="flex-1 bg-gray-50">
            <nav class="bg-white shadow px-6 py-4 flex justify-between items-center sticky top-0 z-40">
                <div class="text-md font-semibold text-gray-800">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>

                <!-- Navigasi kanan: bisa disesuaikan -->
                <div class="flex items-center gap-4">


                    <!-- Info User -->
                    <span class="inline-flex items-center gap-2 text-sm text-gray-700">
                        <div class="relative inline-block text-left">
                            <button id="notificationButton" class="relative focus:outline-none">
                                <span class="material-symbols-outlined text-gray-600 text-2xl">notifications</span>

                                @if (auth()->user()->unreadNotifications->count())
                                    <span
                                        class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </button>

                            <div id="notificationDropdown"
                                class="hidden absolute right-0 mt-2 w-80 bg-white shadow-lg rounded-md z-50">
                                <div class="p-4 font-semibold border-b text-gray-700">Notifikasi</div>
                                <div class="max-h-72 overflow-y-auto divide-y divide-gray-200">
                                    @php
                                        $notifikasiTerbaru = auth()->user()->notifications->take(3);
                                    @endphp

                                    @forelse($notifikasiTerbaru as $notif)
                                        @php
                                            $data = $notif->data;
                                            $source = $data['source'] ?? 'lainnya';
                                            $icon = match ($source) {
                                                'permintaan_olah_data' => 'assignment',
                                                'tabel_statistik' => 'bar_chart',
                                                'publikasi' => 'campaign',
                                                default => 'notifications',
                                            };
                                            $iconColor = match ($source) {
                                                'permintaan_olah_data' => 'text-blue-500',
                                                'tabel_statistik' => 'text-green-500',
                                                'publikasi' => 'text-purple-500',
                                                default => 'text-gray-400',
                                            };
                                        @endphp

                                        <a href="{{ $data['url'] }}"
                                            onclick="event.preventDefault(); bacaDanRedirect('{{ $notif->id }}', '{{ $data['url'] }}')"
                                            class="flex px-4 py-3 hover:bg-gray-100 text-sm gap-3 items-start justify-between">

                                            {{-- Kiri: Ikon jenis --}}
                                            <span
                                                class="material-symbols-outlined {{ $iconColor }} text-lg mt-1">{{ $icon }}</span>

                                            {{-- Tengah: Isi notifikasi --}}
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-800">{{ $data['title'] }}</div>
                                                <div class="text-gray-600 text-xs">{{ $data['body'] }}</div>
                                                @if (isset($data['from']))
                                                    <div class="text-[11px] text-gray-400">Dari: {{ $data['from'] }}
                                                    </div>
                                                @endif
                                                <div class="text-[11px] text-gray-400">
                                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                                </div>
                                            </div>

                                            {{-- Kanan: Ikon link --}}
                                            <span
                                                class="material-symbols-outlined text-gray-400 text-base mt-1">chevron_right</span>
                                        </a>

                                    @empty
                                        <div class="p-4 text-gray-500 text-sm">Tidak ada notifikasi.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>



                        <!-- Info User -->
                        <div x-data="{ open: false }" class="relative">
                            <!-- Foto Profil / Inisial -->
                            <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-700">
                                <span
                                    class="inline-flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full text-xs font-bold">
                                    {{ strtoupper(implode('',array_map(function ($part) {return strtoupper($part[0]);}, array_slice(explode(' ', Auth::user()->name), 0, 3)))) }}
                                </span>
                                <span class="hidden sm:inline-block">{{ Auth::user()->name }}</span>

                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-lg z-10">
                                <div class="py-2">

                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                                        Edit Profile
                                    </a>

                                    <form method="POST" action="{{ route('logout') }}" class="block">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-md">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </span>


                </div>

            </nav>
            <div class="">
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        const button = document.getElementById('notificationButton');
        const dropdown = document.getElementById('notificationDropdown');

        button.addEventListener('click', function() {
            dropdown.classList.toggle('hidden');

            // Hanya tandai sebagai dibaca saat pertama kali dropdown dibuka
            if (!dropdown.classList.contains('hidden')) {
                fetch('/notifikasi/baca-semua', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    // Hilangkan badge angka
                    const badge = button.querySelector('span.bg-red-600');
                    if (badge) badge.remove();
                });
            }
        });
    </script>





    @stack('scripts')
</body>

</html>
