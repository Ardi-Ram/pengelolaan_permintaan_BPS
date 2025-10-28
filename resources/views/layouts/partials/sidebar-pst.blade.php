  <aside class="w-64 bg-blue-900 shadow-lg sticky top-0 self-start h-screen flex flex-col">
      <header class="flex justify-between items-center gap-x-2 p-6 border-b border-blue-700">
          <a class="flex items-center gap-x-2 text-lg font-semibold text-white" href="#">
              <img src="{{ asset('images/bps-logo.png') }}" alt="Logo BPS" class="h-8 w-auto">
              Petugas PST
          </a>
      </header>

      <!-- Navigation -->
      <nav class="h-[calc(100vh-80px)] py-4 overflow-y-auto custom-scrollbar  mb-4">
          <ul class="space-y-2 text-white">

              <!-- Dashboard -->
              <li>
                  <a href="{{ route('dashboard2') }}"
                      class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                   {{ request()->routeIs('dashboard2')
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
                          <a href="{{ route('permintaanolahdata.form') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                           {{ request()->routeIs('permintaanolahdata.form')
                               ? 'bg-white/10 backdrop-blur-md text-white'
                               : 'hover:bg-white/10 hover:backdrop-blur-sm text-gray-200' }}">
                              <span class="material-symbols-outlined">assignment_add</span>
                              Form Permintaan
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('permintaanolahdata.tugas') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm transition
                           {{ request()->routeIs('permintaanolahdata.tugas')
                               ? 'bg-white/10 backdrop-blur-md text-white'
                               : 'hover:bg-white/10 hover:backdrop-blur-sm text-gray-200' }}">

                              <span class="material-symbols-outlined">person_add</span>
                              Penugasan Data
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('permintaanolahdata.status') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                           {{ request()->routeIs('permintaanolahdata.status')
                               ? 'bg-white/10 backdrop-blur-md text-white'
                               : 'hover:bg-white/10 hover:backdrop-blur-sm text-gray-200' }}">
                              <span class="material-symbols-outlined">monitoring</span>
                              Status Data
                          </a>
                      </li>
                  </ul>
              </li>

              <!-- Tabel Dinamis -->
              <li class="border-t border-blue-700 pt-2">
                  <div class="px-3 py-2 text-xs uppercase tracking-wide font-medium text-gray-300">
                      Tabel Statistik
                  </div>
                  <ul class="ml-2 space-y-1">
                      <li>
                          <a href="{{ route('tabel-dinamis.create') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                           {{ request()->routeIs('tabel-dinamis.form') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                              <span class="material-symbols-outlined">note_add</span>
                              Form Pendaftaran
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('tabel-dinamis.penugasan') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                           {{ request()->routeIs('tabel-dinamis.penugasan') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                              <span class="material-symbols-outlined">assignment_ind</span>
                              Penugasan Tabel
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('tabel-dinamis.status') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                           {{ request()->routeIs('tabel-dinamis.status') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                              <span class="material-symbols-outlined">monitoring</span>
                              Status Tabel
                          </a>
                      </li>
                  </ul>
              </li>

              <!-- Penugasan Tabel SIAGA -->
              <li class="border-t border-blue-700 pt-2">
                  <div class="px-3 py-2 text-xs uppercase tracking-wide font-medium text-gray-300">
                      Tabel Publikasi
                  </div>
                  <ul class="ml-2 space-y-1">
                      <li>
                          <a href="{{ route('siaga.pst.penugasan') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                           {{ request()->routeIs('siaga.pst.penugasan') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                              <span class="material-symbols-outlined">table_view</span>
                              Daftar Penugasan
                          </a>
                      </li>
                  </ul>
              </li>

              <!-- Data Mikro -->
              <li class="border-t border-blue-700 pt-2">
                  <div class="px-3 py-2 text-xs uppercase tracking-wide font-medium text-gray-300">
                      Data Mikro
                  </div>
                  <ul class="ml-2 space-y-1">
                      <li>
                          <a href="{{ route('data-mikro.index') }}"
                              class="flex items-center gap-x-3 py-2 px-3 text-sm rounded-md transition
                           {{ request()->routeIs('micro-data.index') ? 'bg-blue-700 text-white' : 'hover:bg-blue-800 text-gray-200' }}">
                              <span class="material-symbols-outlined">dataset</span>
                              Mikro Data
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
          </ul>
      </nav>
  </aside>
