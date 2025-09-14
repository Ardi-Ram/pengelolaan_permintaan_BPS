@extends('layouts.admin')

@section('content')
    <div class="max-w-full bg-white  rounded-lg m-5 border border-gray-300">
        <h2 class="text-xl font-semibold mb-4 border-b border-gray-300 p-6">Tambah User</h2>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('tambah.user') }}" class="p-6 bg-white rounded-lg shadow-md">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-10 gap-6">
                {{-- KIRI - 70% --}}
                <div class="md:col-span-7">
                    <!-- Name -->
                    <div class="mb-4">
                        <label for="name" class="block font-medium mb-1">Name</label>
                        <input id="name" name="name" type="text" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mb-4">
                        <label for="email" class="block font-medium mb-1">Email</label>
                        <input id="email" name="email" type="email" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="block font-medium mb-1">Password</label>
                        <input id="password" name="password" type="password" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
                        <p class="text-sm text-gray-500 mt-1">Sandi minimal harus 8 karakter.</p>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>


                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label for="password_confirmation" class="block font-medium mb-1">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    <div class=" ">
                        <button type="submit"
                            class="border-2 border-gray-500 bg-white-100 text-gray-800 px-6 py-2 rounded-md transition">
                            Tambah User
                        </button>
                    </div>
                </div>

                {{-- KANAN - 30% --}}
                <div class="md:col-span-3">
                    <div class="mb-4">
                        <label class="block font-medium mb-2 ">Pilih Role</label>
                        <div class="space-y-2 ">
                            @php
                                $roles = [
                                    'petugas_pst' => 'Petugas PST',
                                    'pengolah_data' => 'Pengolah_data',
                                ];
                            @endphp

                            @foreach ($roles as $value => $label)
                                <label
                                    class="flex items-center space-x-2 border border-gray-200 p-2 rounded hover:bg-gray-50 cursor-pointer">
                                    <input type="radio" name="role" value="{{ $value }}"
                                        class="text-blue-600 focus:ring-blue-500"
                                        {{ old('role') == $value ? 'checked' : '' }} required>
                                    <span>{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>
                </div>
            </div>

            {{-- BUTTON TAMBAH USER --}}

        </form>

    </div>
@endsection
