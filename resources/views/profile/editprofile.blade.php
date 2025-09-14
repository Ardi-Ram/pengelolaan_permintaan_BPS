@php
    if (Auth::user()->hasRole('admin')) {
        $layout = 'layouts.admin';
    } elseif (Auth::user()->hasRole('petugas_pst')) {
        $layout = 'layouts.petugas';
    } else {
        $layout = 'layouts.pengolah';
    }
@endphp

@extends($layout)

@section('title', 'Profil Saya')

@section('content')
    <h1 class="text-2xl font-bold m-6">Profil Saya</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 m-5">

        {{-- Form Update Nama --}}
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-semibold mb-4">Ubah Nama</h3>
            @if (session('status_name'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('status_name') }}
                </div>
            @endif
            <form method="POST" action="{{ route('profile.update.name') }}">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="border rounded w-full p-2" required>
                    @error('name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        {{-- Form Update Password --}}
        <div class="bg-white p-6 shadow rounded">
            <h3 class="text-lg font-semibold mb-4">Ubah Password</h3>
            @if (session('status_password'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                    {{ session('status_password') }}
                </div>
            @endif
            <form method="POST" action="{{ route('profile.update.password') }}">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-sm font-medium">Password Lama</label>
                    <input type="password" name="current_password" class="border rounded w-full p-2" required>
                    @error('current_password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium">Password Baru</label>
                    <input type="password" name="password" class="border rounded w-full p-2" required>
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="border rounded w-full p-2" required>
                </div>

                <div class="mt-4">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                        Simpan
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
