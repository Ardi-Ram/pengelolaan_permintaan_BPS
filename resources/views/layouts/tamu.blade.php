<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Penting untuk responsivitas -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Kunjungan')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script> <!-- Menggunakan CDN resmi Tailwind -->

    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> <!-- Alpine.js untuk toggle menu -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <link rel="icon" href="{{ asset('images/bps-logo.png') }}" type="image/png">

    <style>
        /* Gaya dasar untuk Material Symbols Outlined */
        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        /* Gaya untuk DataTable agar tetap rapi */
        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 0.5rem !important;
        }

        <style>

        /* Rapikan dropdown & pagination */
        .dataTables_length label {
            @apply text-sm text-gray-700 flex items-center gap-2;
        }

        .dataTables_length select {
            @apply border border-gray-300 rounded px-2 py-1 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-100;
        }

        .dataTables_paginate {
            @apply flex items-center gap-1;
        }

        .dataTables_paginate .paginate_button {
            @apply px-2 py-1 rounded text-sm text-gray-700 hover:bg-blue-100 transition;
        }

        .dataTables_paginate .paginate_button.current {
            @apply bg-blue-600 text-white;
        }
    </style>

    </style>
</head>

<body class="bg-gray-50" x-data="{ open: false }"> <!-- x-data untuk Alpine.js, bg-gray-50 untuk body -->
    <!-- Navbar -->
    <nav class="bg-blue-800 shadow-md text-white border-b border-gray-50 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo -->
                <div class="flex items-center space-x-2 font-bold text-lg text-white flex-shrink-0">
                    <div class="bg-slate-100 rounded-full p-1">
                        <img src="{{ asset('images/bps-logo.png') }}" alt="BPS Logo" class="w-6 h-6 object-contain" />
                    </div>
                    <span>Status Permintaan Data</span>
                </div>

                <!-- Main Navigation Links (Desktop) -->
                <div class="hidden sm:flex items-center space-x-2">
                    <a href="{{ route('kunjungan.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-white/10 text-white hover:bg-white/20 border border-white/30 transition">
                        <span class="material-icons-outlined text-sm">home</span>
                        Home
                    </a>

                    <a href="{{ route('tabel-dinamis.portal') }}"
                        class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-white/10 text-white hover:bg-white/20 border border-white/30 transition">
                        <span class="material-icons-outlined text-sm">table_chart</span>
                        Tabel Statistik
                    </a>
                    <a href="{{ route('data-mikro.public.index') }}"
                        class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-white/10 text-white hover:bg-white/20 border border-white/30 transition">
                        <span class="material-icons-outlined text-sm">dataset</span>
                        Data Mikro
                    </a>

                    @if (Route::has('login'))
                        @auth
                            @php
                                $user = Auth::user();
                                if ($user->hasRole('admin')) {
                                    $dashboardRoute = route('admin.index');
                                    $label = 'Admin';
                                    $icon = 'shield';
                                } elseif ($user->hasRole('petugas_pst')) {
                                    $dashboardRoute = route('dashboard2');
                                    $label = 'PST';
                                    $icon = 'badge';
                                } elseif ($user->hasRole('pengolah_data')) {
                                    $dashboardRoute = route('pengolah.dashboard');
                                    $label = 'Pengolah';
                                    $icon = 'work';
                                } else {
                                    $dashboardRoute = url('/dashboard');
                                    $label = 'User';
                                    $icon = 'person';
                                }
                            @endphp

                            <a href="{{ $dashboardRoute }}"
                                class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-white/10 text-white hover:bg-white/20 border border-white/30 transition">
                                <span class="material-icons-outlined text-sm">{{ $icon }}</span>
                                {{ $label }}
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="ml-2">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-red-500/80 text-white hover:bg-red-600 transition">
                                    <span class="material-symbols-outlined text-sm">logout</span>
                                    Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center gap-1 text-xs font-medium px-2.5 py-1 rounded-full bg-white/10 text-white hover:bg-white/20 border border-white/30 transition">
                                <span class="material-icons-outlined text-sm">login</span>
                                Login
                            </a>
                        @endauth
                    @endif
                </div>

                <!-- Hamburger Button (Mobile) -->
                <div class="flex items-center sm:hidden">
                    <button type="button" class="text-white hover:text-blue-200 focus:outline-none"
                        @click="open = !open">
                        <span class="material-icons-outlined text-2xl">menu</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="sm:hidden" x-show="open" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 -translate-y-full" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-full"
            @click.away="open = false">
            <div class="pt-2 pb-3 space-y-1 bg-blue-700 text-white">
                <a href="{{ route('kunjungan.index') }}"
                    class="block px-4 py-2 text-base font-medium hover:bg-blue-600 transition flex items-center gap-2">
                    <span class="material-icons-outlined text-xl">home</span>
                    Home
                </a>
                <a href="{{ route('tabel-dinamis.portal') }}"
                    class="block px-4 py-2 text-base font-medium hover:bg-blue-600 transition flex items-center gap-2">
                    <span class="material-icons-outlined text-xl">table_chart</span>
                    Tabel
                </a>
                <a href="{{ route('data-mikro.public.index') }}"
                    class="block px-4 py-2 text-base font-medium hover:bg-blue-600 transition flex items-center gap-2">
                    <span class="material-icons-outlined text-xl">dataset</span>
                    Data Mikro
                </a>

                @if (Route::has('login'))
                    @auth
                        @php
                            $user = Auth::user();
                            if ($user->hasRole('admin')) {
                                $dashboardUrl = route('admin.index');
                                $label = 'Admin';
                                $icon = 'shield';
                            } elseif ($user->hasRole('petugas_pst')) {
                                $dashboardUrl = route('dashboard2');
                                $label = 'PST';
                                $icon = 'badge';
                            } elseif ($user->hasRole('pengolah_data')) {
                                $dashboardUrl = route('pengolah.dashboard');
                                $label = 'Pengolah';
                                $icon = 'work';
                            } else {
                                $dashboardUrl = url('/');
                                $label = 'User';
                                $icon = 'person';
                            }
                        @endphp

                        <a href="{{ $dashboardUrl }}"
                            class="block px-4 py-2 text-base font-medium hover:bg-blue-600 transition flex items-center gap-2">
                            <span class="material-icons-outlined text-xl">{{ $icon }}</span>
                            {{ $label }}
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-base font-medium text-red-100 hover:bg-red-600 hover:text-white transition flex items-center gap-2">
                                <span class="material-symbols-outlined text-xl">logout</span> Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 text-base font-medium hover:bg-blue-600 transition flex items-center gap-2">
                            <span class="material-icons-outlined text-xl">login</span>
                            Log in
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>



    <!-- Main Content -->
    <main class="bg-white  min-h-[calc(100vh-theme(spacing.16)-theme(spacing.48))]">
        <!-- Tinggi min-h disesuaikan agar footer tidak menimpa konten -->
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-blue-800 text-white border-t-4 border-blue-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div
                class="grid grid-cols-1 md:grid-cols-{{ 1 + count($footerGroups) }} gap-4 md:gap-6 lg:gap-8 text-center md:text-left">
                <!-- Kolom pertama: Tentang Kami -->
                <div class="max-w-md mx-auto md:mx-0">
                    <h3 class="text-xl font-bold mb-4">Badan Pusat STatistik</h3>
                    <p class="text-gray-200 text-sm leading-relaxed">
                        Badan Pusat Statistik Provinsi Kepulauan Bangka Belitung
                        (BPS-Statistics Kepulauan Bangka Belitung)
                        Komplek Perkantoran Terpadu Pemerintah Provinsi Kepulauan Bangka Belitung
                        Telp: (0717) 439422
                        Mailbox: bps1900@bps.go.id
                    </p>
                    <div class="flex justify-center md:justify-start space-x-4 mt-4">
                        <!-- Facebook -->
                        <a href="#"
                            class="bg-[#1877F2] text-white w-10 h-10 flex items-center justify-center rounded-full hover:opacity-80 transition">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <!-- X (Twitter) -->
                        <a href="#"
                            class="bg-black text-white w-10 h-10 flex items-center justify-center rounded-full hover:opacity-80 transition">
                            <i class="fab fa-x-twitter text-lg"></i>
                        </a>
                        <!-- Instagram -->
                        <a href="#"
                            class="bg-gradient-to-tr from-yellow-400 via-pink-500 to-purple-600 text-white w-10 h-10 flex items-center justify-center rounded-full hover:opacity-80 transition">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <!-- YouTube -->
                        <a href="#"
                            class="bg-[#FF0000] text-white w-10 h-10 flex items-center justify-center rounded-full hover:opacity-80 transition">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>

                <!-- Kolom berikutnya: Dinamis, 1 group = 1 kolom -->
                @foreach ($footerGroups as $footerGroup)
                    <div>
                        <h3 class="text-xl font-bold mb-4">{{ $footerGroup->name }}</h3>
                        <ul class="space-y-2 text-sm">
                            @foreach ($footerGroup->links->sortBy('order') as $link)
                                <li>
                                    <a href="{{ $link->url }}" target="_blank"
                                        class="text-gray-200 hover:text-blue-200 transition">
                                        {{ $link->label }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>

            <!-- Footer bottom -->
            <div class="border-t border-blue-600 mt-8 pt-4 text-sm text-center text-gray-300">
                &copy; 2025 Layanan Data. Semua Hak Dilindungi.
            </div>
        </div>
    </footer>


    @stack('scripts')
</body>

</html>
