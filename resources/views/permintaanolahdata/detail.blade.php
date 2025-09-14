@extends('layouts.petugas')
@section('content')
    <div class="min-h-screen m-5 bg-white border border-gray-300">
        <div class="mb-8 border-b border-gray-300 p-6">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-2 bg-blue-500 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Permintaan Data</h1>
            </div>

        </div>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->


            <!-- Main Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 p-6">
                <!-- Left Column - Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Dasar</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Transaction Code -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Kode Transaksi</p>
                                    <p class="text-base font-mono text-gray-900 bg-gray-50 px-3 py-2 rounded border">
                                        {{ $data->pemilikData->kode_transaksi ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Data Owner -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 mb-2">Pemilik Data</p>
                                    <div class="space-y-1 text-sm">
                                        <div class="flex">
                                            <span class="w-16 text-gray-500">Nama:</span>
                                            <span
                                                class="text-gray-900 font-medium">{{ $data->pemilikData->nama_pemilik ?? '-' }}</span>
                                        </div>
                                        <div class="flex">
                                            <span class="w-16 text-gray-500">Email:</span>
                                            <span class="text-gray-900">{{ $data->pemilikData->email ?? '-' }}</span>
                                        </div>
                                        <div class="flex">
                                            <span class="w-16 text-gray-500">Instansi:</span>
                                            <span class="text-gray-900">{{ $data->pemilikData->instansi ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Request Details Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Detail Permintaan</h2>
                        </div>
                        <div class="p-6 space-y-6">
                            <!-- Title -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Judul Permintaan</p>
                                    <p class="text-base text-gray-900 font-semibold">{{ $data->judul_permintaan }}</p>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Deskripsi</p>
                                    <p class="text-base text-gray-900 leading-relaxed">{{ $data->deskripsi }}</p>
                                </div>
                            </div>

                            <!-- Reason -->
                            <div class="flex items-start space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 mb-1">Alasan</p>
                                    <p class="text-base text-gray-900 leading-relaxed">{{ $data->alasan }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Status & Assignment -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Status</h2>
                        </div>
                        <div class="p-6 text-center">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                                @if ($data->status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($data->status == 'approved') bg-green-100 text-green-800
                                @elseif($data->status == 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($data->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Category Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Kategori</h2>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                        </path>
                                    </svg>
                                </div>
                                <span
                                    class="text-sm font-medium text-gray-900">{{ $data->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Team Assignment Card -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-900">Tim Penanganan</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <!-- PST Officer -->
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Petugas PST</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $data->petugasPst->name ?? '-' }}</p>
                                </div>
                            </div>

                            <!-- Data Processor -->
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Pengolah Data</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $data->pengolah->name ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="mt-8 flex justify-end">
                <a href="{{ route('permintaanolahdata.status') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection
