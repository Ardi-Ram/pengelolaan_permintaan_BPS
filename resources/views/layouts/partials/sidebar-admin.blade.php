 <aside class="w-64  shadow-lg sticky top-0 self-start h-screen flex flex-col">
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
