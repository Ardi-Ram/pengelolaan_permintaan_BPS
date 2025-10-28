     <aside class="w-64 bg-blue-900 shadow-lg sticky top-0 self-start h-screen flex flex-col">
         <header class="flex justify-between items-center gap-x-2 p-6 border-b border-blue-700">
             <a class="flex items-center gap-x-2 font-semibold text-xl text-white" href="#">

                 <img src="{{ asset('images/bps-logo.png') }}" alt="Logo BPS" class="h-8 w-auto">
                 Pengolah Data
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
