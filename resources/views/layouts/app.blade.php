<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- jQuery -->
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

    {{-- CSS tambahan --}}
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
    @stack('styles')
</head>

<body class="bg-gray-50">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}

        @include('layouts.sidebar')


        {{-- Main --}}
        <main class="flex-1">
            @include('layouts.partials.navbar')
            @yield('content')
        </main>
    </div>


    @stack('scripts')
    @yield('scripts')
</body>

</html>
