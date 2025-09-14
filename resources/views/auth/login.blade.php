<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Icon Font -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>

<body class="antialiased">

    <div class="flex items-center justify-center min-h-screen bg-gray-50">
        <div class="flex flex-col md:flex-row w-full max-w-4xl bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Bagian kiri -->
            <div
                class="bg-gradient-to-br from-blue-600 to-indigo-700 text-white p-8 md:p-12 md:w-1/2 relative overflow-hidden flex flex-col justify-center items-center text-center">
                <div class="absolute inset-0 z-0 opacity-10">
                    <svg class="w-full h-full" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <defs>
                            <pattern id="pattern-circles" x="0" y="0" width="20" height="20"
                                patternUnits="userSpaceOnUse" patternContentUnits="userSpaceOnUse">
                                <circle id="pattern-circle" cx="10" cy="10" r="2"
                                    class="text-blue-400 opacity-60"></circle>
                            </pattern>
                        </defs>
                        <rect id="pattern-rect" x="0" y="0" width="100%" height="100%" fill="url(#pattern-circles)">
                        </rect>
                    </svg>
                </div>
                <div class="relative z-10 max-w-md mx-auto text-center">
                    <img src="/images/login.png" alt="Login Icon" class="mx-auto mb-4 bg-white p-3 rounded-r-full"
                        width="200" height="200">
                    <h1 class="text-3xl lg:text-3xl font-extrabold mb-4 leading-tight">Selamat Datang</h1>
                </div>
            </div>

            <!-- Bagian kanan (form) -->
            <div class="p-8 md:p-12 md:w-1/2 flex items-center justify-center">
                <div class="w-full max-w-sm rounded-lg bg-white">
                    <div class="mb-6 text-center">
                        <span class="material-symbols-outlined text-5xl text-blue-600 mb-2">account_circle</span>
                        <h1 class="text-3xl font-extrabold text-gray-800">Login</h1>
                        <p class="text-gray-500 mt-2 text-sm">Silakan masukkan kredensial Anda untuk melanjutkan.</p>
                    </div>

                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email</label>
                            <input id="email" type="email" name="email" :value="old('email')" required
                                autofocus
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                                placeholder="contoh@email.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <label for="password"
                                class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 placeholder-gray-400"
                                placeholder="********" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <div class="text-right mt-2">
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-sm text-blue-600 hover:underline">Lupa password?</a>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-gray-300 text-blue-600 w-4 h-4" />
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">Ingat saya</label>
                        </div>

                        <button type="submit"
                            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition duration-300 hover:shadow-lg">
                            Masuk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
