 <nav class="bg-white shadow px-6 py-4 flex justify-between items-center sticky top-0 z-40">
     <div class="flex items-center space-x-2 text-md font-semibold text-gray-800">

         <!-- Icon Kalender -->
         <span class="material-symbols-outlined text-blue-600 text-lg">
             calendar_month
         </span>

         <!-- Teks Tanggal -->
         <span>
             {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
         </span>
     </div>

     <!-- Navigasi kanan: bisa disesuaikan -->
     <div class="flex items-center gap-4">
         <!-- Info User -->
         <span class="inline-flex items-center gap-2 text-sm text-gray-700">

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
