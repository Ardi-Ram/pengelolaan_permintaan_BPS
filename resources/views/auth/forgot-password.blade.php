<x-guest-layout>
    <div
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 via-white to-blue-200 px-4">
        <div class="w-full max-w-md bg-white/70 backdrop-blur-lg border border-white/30 shadow-xl rounded-2xl p-8">

            <!-- Header -->
            <div class="text-center mb-6">
                <div class="flex justify-center mb-3">
                    <div class="p-3 bg-blue-500/20 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0zM12 20h.01M12 4h.01" />
                        </svg>
                    </div>
                </div>
                <h1 class="text-2xl font-semibold text-gray-800">Lupa Kata Sandi?</h1>
                <p class="text-gray-600 text-sm mt-1">
                    Masukkan email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Alamat Email')" class="text-gray-700 font-medium" />
                    <x-text-input id="email"
                        class="block mt-1 w-full border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 rounded-lg"
                        type="email" name="email" :value="old('email')" placeholder="nama@email.com" required
                        autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                        Kembali ke Login
                    </a>

                    <x-primary-button class="bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 inline-block mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 12H8m8 0a4 4 0 11-8 0 4 4 0 018 0zM12 20h.01M12 4h.01" />
                        </svg>
                        Kirim Tautan Reset
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
