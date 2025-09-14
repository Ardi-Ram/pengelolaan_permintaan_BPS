<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Status Permintaan Data - Sedang Diproses</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, sans-serif; background-color: #f4f4f4;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr style="background-color: #1e3a8a; color: #ffffff;">
                        <td style="padding: 30px 40px; text-align: center;">
                            <img src="{{ $logoUrl }}" alt="Logo BPS" width="50" style="margin-bottom: 10px;">
                            <h1 style="margin: 0; font-size: 24px;">Badan Pusat Statistik</h1>
                            <p style="margin: 0; font-size: 14px;">Kepulauan Bangka Belitung</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <h2 style="color: #1e3a8a; margin-bottom: 10px;">Halo,
                                {{ $permintaan->pemilikData->nama_pemilik }}</h2>
                            <p style="color: #444; font-size: 15px; margin-bottom: 16px;">
                                Permintaan data Anda saat ini sedang <strong>diproses</strong> oleh Tim Pengolah Data
                                kami.
                            </p>

                            <!-- Info Box -->
                            <div
                                style="background-color: #f9fafb; border-left: 4px solid #2563eb; padding: 16px; margin-bottom: 24px; font-size: 15px;">
                                <p style="margin: 0 0 8px 0;"><strong>Judul
                                        Permintaan:</strong><br>{{ $permintaan->judul_permintaan }}</p>
                                <p style="margin: 0;"><strong>Kode
                                        Transaksi:</strong><br>{{ $permintaan->pemilikData->kode_transaksi ?? '-' }}</p>
                            </div>

                            <p style="color: #444; font-size: 15px; margin-bottom: 30px;">
                                Anda dapat memantau status permintaan ini melalui halaman berikut:
                            </p>

                            <!-- Button -->
                            <div style="text-align: center; margin-bottom: 40px;">
                                <a href="{{ route('kunjungan.index', ['kode' => $permintaan->kode_transaksi]) }}"
                                    style="display: inline-block; background-color: #1e3a8a; color: #ffffff; padding: 14px 28px; font-size: 15px; border-radius: 6px; text-decoration: none;">
                                    Cek Status Permintaan
                                </a>
                            </div>

                            <p style="font-size: 13px; color: #6b7280;">
                                Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi petugas kami.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f4f4f4; padding: 24px; text-align: center; font-size: 12px; color: #999;">
                            Email ini dikirim otomatis oleh sistem BPS Kepulauan Bangka Belitung.<br>
                            Mohon untuk tidak membalas email ini.
                            <div style="margin-top: 10px; color: #bbb;">&copy; {{ date('Y') }} BPS Babel</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
