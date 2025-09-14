@extends('layouts.admin')

@section('content')
    <div class="max-w-full grid grid-cols-1 md:grid-cols-2 gap-8 m-5 bg-white rounded-lg p-6 border border-gray-300">
        <!-- Chart: Jumlah User -->
        <div class="border border-gray-300 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2 ">Jumlah User per Role</h2>
            <div style="height: 250px">
                <canvas id="userRoleChart"></canvas>
            </div>
        </div>
        <!-- Chart: Permintaan per Status -->
        <div class="border border-gray-300 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Permintaan Data per Status</h2>
            <div style="height: 250px">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- Chart: Permintaan per Bulan -->
        <div class="col-span-1 md:col-span-2 border border-gray-300 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Permintaan Data per Bulan</h2>
            <div style="height: 250px">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        {{-- BAGIAN BARU: Statistik Google Analytics --}}
        <div class="col-span-1 md:col-span-2 border border-gray-300 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-4">Statistik Google Analytics</h2>
            <div id="ga-auth-button" class="mb-4 text-center"></div>
            <div id="ga-data-output"
                class="bg-blue-50 p-4 rounded-lg border border-blue-200 text-blue-800 min-h-[120px] flex items-center justify-center text-center">
                Memuat data Google Analytics...
            </div>
        </div>
        {{-- AKHIR BAGIAN BARU --}}

    </div>
@endsection

@push('scripts')
    {{-- Skrip Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart 1: User per Role
        new Chart(document.getElementById('userRoleChart'), {
            type: 'bar',
            data: {
                labels: ['Petugas PST', 'Pengolah Data'],
                datasets: [{
                    label: 'Jumlah User',
                    data: [{{ $data['petugas_pst'] }}, {{ $data['pengolah_data'] }}],
                    backgroundColor: ['#3b82f6', '#9EC6F3']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        // Chart 2: Permintaan per Status
        new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: ['Antrian', 'Proses', 'Selesai'],
                datasets: [{
                    data: [
                        {{ $data['status']['antrian'] }},
                        {{ $data['status']['proses'] }},
                        {{ $data['status']['selesai'] }}
                    ],
                    backgroundColor: ['#93c5fd', '#60a5fa', '#3b82f6']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        // Chart 3: Permintaan per Bulan
        new Chart(document.getElementById('monthlyChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($data['bulan']) !!},
                datasets: [{
                    label: 'Permintaan Data',
                    data: {!! json_encode($data['permintaan_bulanan']) !!},
                    fill: false,
                    borderColor: '#6366f1',
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>

    {{-- PENTING: Ganti gapi.js dengan library Google Identity Services (GIS) --}}
    <script async defer src="https://accounts.google.com/gsi/client"></script>
    <script>
        // === KODE JAVASCRIPT UNTUK GOOGLE ANALYTICS (MENGGUNAKAN GIS) ===

        // === KONFIGURASI PENTING ===
        // Ganti dengan Client ID Anda yang asli dari Google Cloud Console
        const CLIENT_ID = '438253362102-hof385j289uf1l36kebh938qqtb40iba.apps.googleusercontent.com';

        // Ganti dengan ID properti GA4 Anda (HANYA ANGKA, misal: '123456789')
        const GA4_PROPERTY_ID = '497594710'; // <--- ID properti GA4 Anda

        // Scope yang dibutuhkan untuk membaca data Google Analytics
        const SCOPES = 'https://www.googleapis.com/auth/analytics.readonly';

        let tokenClient; // Untuk menyimpan objek token client GIS
        let accessToken; // Untuk menyimpan token akses yang didapat

        // --- Fungsi Inisialisasi GIS ---

        /**
         * Dipanggil setelah library GIS dimuat.
         * Menginisialisasi token client untuk otentikasi OAuth 2.0.
         */
        function initializeGis() {
            tokenClient = google.accounts.oauth2.initTokenClient({
                client_id: CLIENT_ID,
                scope: SCOPES,
                callback: (tokenResponse) => {
                    if (tokenResponse && tokenResponse.access_token) {
                        accessToken = tokenResponse.access_token;
                        // Setelah mendapatkan token, inisialisasi gapi.client
                        // dan panggil queryGoogleAnalytics
                        gapi.client.setToken({
                            access_token: accessToken
                        });
                        updateSignInStatus(true);
                    } else {
                        console.error('Failed to get access token:', tokenResponse);
                        updateSignInStatus(false);
                    }
                },
                error_callback: (error) => {
                    console.error('GIS initialization error:', error);
                    document.getElementById('ga-data-output').innerHTML =
                        '<p class="text-red-600 font-semibold">Terjadi kesalahan saat otorisasi Google. Periksa konsol browser.</p>';
                    updateSignInStatus(false);
                }
            });
            // Setelah tokenClient siap, kita bisa menampilkan tombol otorisasi
            updateSignInStatus(false); // Awalnya belum sign-in
        }

        /**
         * Memperbarui status sign-in di UI.
         */
        function updateSignInStatus(isSignedIn) {
            const authButtonDiv = document.getElementById('ga-auth-button');
            const dataOutputDiv = document.getElementById('ga-data-output');

            if (isSignedIn) {
                authButtonDiv.innerHTML =
                    '<button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="handleSignOutClick()">Sign Out Google Analytics</button>';
                dataOutputDiv.innerHTML = '<p>Sedang mengambil data Google Analytics...</p>';
                // Setelah sign-in, pastikan gapi.client siap sebelum memanggil queryGoogleAnalytics
                gapi.load('client', () => {
                    gapi.client.setToken({
                        access_token: accessToken
                    }); // Set token lagi
                    gapi.client.discover('https://analyticsdata.googleapis.com/$discovery/rest?version=v1beta')
                        .then(() => {
                            queryGoogleAnalytics();
                        })
                        .catch(err => {
                            console.error('Error discovering API:', err);
                            dataOutputDiv.innerHTML =
                                '<p class="text-red-600 font-semibold">Gagal memuat API Analytics. Periksa konsol.</p>';
                        });
                });
            } else {
                authButtonDiv.innerHTML =
                    '<button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded" onclick="handleSignInClick()">Authorize Google Analytics</button>';
                dataOutputDiv.innerHTML = '<p>Silakan klik "Authorize Google Analytics" untuk melihat data statistik.</p>';
            }
        }

        /**
         * Menangani klik tombol Sign In.
         * Meminta token akses dari Google.
         */
        function handleSignInClick() {
            if (tokenClient) {
                tokenClient.requestAccessToken();
            } else {
                console.error('tokenClient not initialized.');
            }
        }

        /**
         * Menangani klik tombol Sign Out.
         * Mencabut token akses.
         */
        function handleSignOutClick() {
            if (accessToken) {
                google.accounts.oauth2.revoke(accessToken, () => {
                    console.log('Access token revoked.');
                    accessToken = null;
                    updateSignInStatus(false);
                });
            }
        }

        /**
         * Mengirim permintaan ke Google Analytics Data API (untuk GA4).
         * Mengambil data pengguna aktif, sesi, dan tampilan halaman selama 30 hari terakhir.
         */
        async function queryGoogleAnalytics() {
            if (!GA4_PROPERTY_ID || GA4_PROPERTY_ID === 'YOUR_GA4_PROPERTY_ID') {
                document.getElementById('ga-data-output').innerHTML =
                    '<p class="text-red-600 font-semibold">Error: Mohon masukkan ID properti GA4 Anda yang valid di kode JavaScript (GA4_PROPERTY_ID).</p>';
                return;
            }

            // Pastikan gapi.client sudah terinisialisasi dan token sudah diset
            if (!gapi.client || !gapi.client.analyticsdata || !accessToken) {
                console.error('gapi.client or analyticsdata not ready, or access token missing.');
                document.getElementById('ga-data-output').innerHTML =
                    '<p class="text-red-600 font-semibold">API belum siap atau token akses hilang. Coba otorisasi ulang.</p>';
                return;
            }

            try {
                const response = await gapi.client.analyticsdata.properties.runReport({
                    property: `properties/${GA4_PROPERTY_ID}`,
                    resource: {
                        dimensions: [{
                                name: 'date'
                            },
                            {
                                name: 'country'
                            }
                        ],
                        metrics: [{
                                name: 'activeUsers'
                            },
                            {
                                name: 'sessions'
                            },
                            {
                                name: 'screenPageViews'
                            }
                        ],
                        dateRanges: [{
                            startDate: '30daysAgo',
                            endDate: 'today'
                        }],
                        orderBys: [{
                            dimension: {
                                dimensionName: 'date'
                            },
                            desc: true
                        }]
                    }
                });

                console.log('API Response:', response.result);
                displayResults(response.result);

            } catch (err) {
                console.error('Error fetching data from Google Analytics Data API:', JSON.stringify(err, null, 2));
                let errorMessage = 'Terjadi kesalahan saat mengambil data. Periksa konsol browser.';
                if (err.result && err.result.error && err.result.error.message) {
                    errorMessage += ` Pesan: ${err.result.error.message}`;
                }
                document.getElementById('ga-data-output').innerHTML =
                    `<p class="text-red-600 font-semibold">${errorMessage}</p>`;
            }
        }

        /**
         * Menampilkan hasil dari API di halaman.
         */
        function displayResults(response) {
            const outputDiv = document.getElementById('ga-data-output');
            outputDiv.innerHTML = '<h3>Data Pengunjung (30 Hari Terakhir):</h3>';

            if (response.rows && response.rows.length > 0) {
                let tableHtml =
                    '<div class="overflow-x-auto"><table class="min-w-full bg-white rounded-lg shadow-sm"><thead><tr><th class="py-2 px-4 border-b">Tanggal</th><th class="py-2 px-4 border-b">Negara</th><th class="py-2 px-4 border-b">Pengguna Aktif</th><th class="py-2 px-4 border-b">Sesi</th><th class="py-2 px-4 border-b">Tampilan Halaman</th></tr></thead><tbody>';
                response.rows.forEach(row => {
                    const date = row.dimensionValues[0]?.value || 'N/A';
                    const country = row.dimensionValues[1]?.value || 'N/A';
                    const activeUsers = row.metricValues[0]?.value || '0';
                    const sessions = row.metricValues[1]?.value || '0';
                    const pageViews = row.metricValues[2]?.value || '0';
                    tableHtml +=
                        `<tr><td class="py-2 px-4 border-b">${date}</td><td class="py-2 px-4 border-b">${country}</td><td class="py-2 px-4 border-b">${activeUsers}</td><td class="py-2 px-4 border-b">${sessions}</td><td class="py-2 px-4 border-b">${pageViews}</td></tr>`;
                });
                tableHtml += '</tbody></table></div>';
                outputDiv.innerHTML += tableHtml;
            } else {
                outputDiv.innerHTML +=
                    '<p class="text-gray-600">Tidak ada data ditemukan untuk periode ini atau properti GA4 Anda. Pastikan ID properti benar dan ada data.</p>';
            }
        }

        // Panggil fungsi inisialisasi GIS saat halaman dimuat
        // `window.onload` akan memastikan DOM sudah siap
        window.onload = initializeGis;
    </script>
@endpush
