<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
    <script src="https://apis.google.com/js/api.js"></script>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BZGKG97YN8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-BZGKG97YN8');
    </script>


    <style>
        /* Aplikasikan font Inter ke seluruh halaman */
        body {
            font-family: 'Inter', sans-serif;
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
    <div class="flex">
        <!-- Sidebar -->
        <aside class="fixed top-0 left-0 h-screen w-60 p-4 shadow-lg bg-white z-50">

            <header class="p-4 flex justify-between items-center gap-x-2 mb-4">
                <a class="flex items-center gap-x-2 font-semibold text-xl text-black" href="#">
                    <img src="{{ asset('images/bps-logo.png') }}" alt="Logo BPS" class="h-8 w-auto">
                    Admin
                </a>
            </header>

            <nav class="h-[calc(100vh-80px)] overflow-y-auto">
                <ul class="space-y-2">

                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('admin.index') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm transition font-semibold border-b border-gray-300 
                {{ request()->routeIs('show.admin.index')
                    ? 'border-l-4  border-blue-500  bg-blue-100 text-blue-800'
                    : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined">dashboard</span> Dashboard
                        </a>
                    </li>

                    <!-- User Management -->
                    <li>
                        <a href="{{ route('show.admin.user') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm transition border-b border-gray-300
                    {{ request()->routeIs('show.admin.user') ? ' bg-blue-50 text-blue-800' : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined">group</span> Users
                        </a>
                    </li>

                    <!-- Kategori -->
                    <li>
                        <a href="{{ route('categories.index') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm font-semibold transition border-b border-gray-300
                    {{ request()->routeIs('categories.index')
                        ? 'border-l-4  border-blue-500  bg-blue-100 text-blue-800'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined">category</span> Kategori & Subjek
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('perantara.index') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm font-semibold transition border-b border-gray-300
                    {{ request()->routeIs('perantara.index')
                        ? 'border-l-4 border-blue-500 bg-blue-100 text-blue-800'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined">sync_alt</span> Perantara
                        </a>
                    </li>

                    <!-- Link & Banner -->
                    <li>
                        <a href="{{ route('admin.links.index') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm font-semibold transition border-b border-gray-300
                    {{ request()->routeIs('admin.links.index')
                        ? 'border-l-4 border-blue-500 bg-blue-100 text-blue-800'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined">link</span> Link & Banner
                        </a>
                    </li>

                    <!-- Footer Links -->
                    <li>
                        <a href="{{ route('admin.footer_links.index') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm font-semibold transition border-b border-gray-300
                    {{ request()->routeIs('admin.footer_links.index')
                        ? 'border-l-4 border-blue-500 bg-blue-100 text-blue-800'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined">link</span> Footer Links
                        </a>
                    </li>

                    <!-- Direktori Data -->
                    <li>
                        <a href="{{ route('pengolah.direktori.view') }}"
                            class="flex items-center gap-x-3 py-2 px-3 text-sm font-semibold transition border-b border-gray-300
                    {{ request()->routeIs('pengolah.direktori.view')
                        ? 'border-l-4  border-blue-500  bg-blue-100 text-blue-800'
                        : 'text-gray-700 hover:bg-gray-100' }}">
                            <span class="material-symbols-outlined">folder_open</span> Direktori Data
                        </a>
                    </li>

                    <!-- Logout -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-x-3 py-2 px-3 text-sm rounded-lg transition text-red-600 hover:bg-red-100">
                                <span class="material-symbols-outlined">logout</span> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </nav>

        </aside>
        <main class="flex-1 ml-60 bg-gray-50 min-h-screen">
            <nav class="bg-white shadow px-6 py-4 flex justify-between items-center sticky top-0 z-40">
                <div class="text-md font-semibold text-gray-800">
                    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                </div>



                <!-- Navigasi kanan: bisa disesuaikan -->
                <div class="flex items-center gap-4">


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

                </div>

            </nav>
            <div class="">
                @yield('content')
            </div>
        </main>
    </div>
    @stack('scripts')
</body>

</html>
