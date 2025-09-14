<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Data Selesai Diproses</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Mobile-first responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
            }

            .content-padding {
                padding: 24px 16px !important;
            }

            .header-padding {
                padding: 32px 16px !important;
            }

            .footer-padding {
                padding: 24px 16px !important;
            }

            .status-card-padding {
                padding: 20px !important;
            }

            .info-card-padding {
                padding: 20px !important;
            }

            .button-padding {
                padding: 24px 0 !important;
            }

            .button-text {
                padding: 14px 24px !important;
                font-size: 15px !important;
            }

            .title-text {
                font-size: 22px !important;
            }

            .header-title {
                font-size: 24px !important;
            }

            .section-title {
                font-size: 16px !important;
            }

            .logo-size {
                width: 50px !important;
                height: 50px !important;
                max-width: 50px !important;
            }
        }
    </style>
</head>

<body
    style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; background: #f8f9fa;">

    <!-- Email Container Table -->
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="margin: 0; padding: 0; width: 100%; background: #f8f9fa;">
        <tr>
            <td align="center" style="padding: 20px 10px;">

                <!-- Main Email Container -->
                <table class="email-container" width="600" cellpadding="0" cellspacing="0" role="presentation"
                    style="max-width: 600px; width: 100%; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08); border: 1px solid #e9ecef;">

                    <!-- Header -->
                    <tr>
                        <td class="header-padding" style="background: #1e3a8a; padding: 40px 32px; text-align: center;">
                            <!-- Logo BPS -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin-bottom: 16px;">
                                <tr>
                                    <td style="text-align: center;">
                                        <img src="{{ $logoUrl }}" alt="Logo BPS" width="60" height="60"
                                            class="logo-size"
                                            style="display: block; margin: 0 auto; width: 60px; height: 60px; max-width: 60px;">
                                    </td>
                                </tr>
                            </table>

                            <h1 class="header-title"
                                style="margin: 0; color: #ffffff; font-size: 26px; font-weight: 600; margin-bottom: 8px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; letter-spacing: -0.5px;">
                                Badan Pusat Statistik
                            </h1>
                            <p
                                style="margin: 0; color: #cbd5e1; font-size: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 400;">
                                Kepulauan Bangka Belitung
                            </p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td class="content-padding" style="padding: 40px 32px;">

                            <!-- Greeting Section -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin-bottom: 32px;">
                                <tr>
                                    <td style="text-align: left;">
                                        <h2 class="title-text"
                                            style="margin: 0; font-size: 24px; color: #1f2937; margin-bottom: 8px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 600; letter-spacing: -0.3px;">
                                            Halo, {{ $permintaan->pemilikData->nama_pemilik }}
                                        </h2>
                                        <p
                                            style="margin: 0; font-size: 16px; color: #6b7280; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.5;">
                                            Permintaan data Anda telah berhasil diselesaikan dan siap untuk diakses.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Status Section -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 6px; margin-bottom: 32px;">
                                <tr>
                                    <td class="status-card-padding" style="padding: 24px; text-align: center;">
                                        <div
                                            style="display: inline-block; background: #0ea5e9; color: white; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px;">
                                            Selesai Diproses
                                        </div>
                                        <p
                                            style="margin: 0; color: #0c4a6e; font-size: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 500;">
                                            Data telah berhasil diproses dan tersedia untuk diunduh
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Request Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin-bottom: 32px;">
                                <tr>
                                    <td>
                                        <h3 class="section-title"
                                            style="margin: 0; margin-bottom: 16px; font-size: 18px; color: #1f2937; font-weight: 600; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                            Detail Permintaan
                                        </h3>
                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                            style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px;">
                                            <tr>
                                                <td style="padding: 20px;">
                                                    <p
                                                        style="margin: 0; color: #6b7280; font-size: 14px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 4px; font-weight: 500;">
                                                        Judul Permintaan
                                                    </p>
                                                    <p
                                                        style="margin: 0; font-size: 16px; color: #1f2937; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-weight: 500;">
                                                        {{ $permintaan->judul_permintaan }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Processing Info -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="margin-bottom: 32px;">
                                <tr>
                                    <td>
                                        <h3 class="section-title"
                                            style="margin: 0; margin-bottom: 16px; font-size: 18px; color: #1f2937; font-weight: 600; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                            Informasi Pengolahan
                                        </h3>

                                        <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                            style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px;">
                                            <tr>
                                                <td class="info-card-padding" style="padding: 24px;">
                                                    <!-- Info Row 1 -->
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                        role="presentation" style="margin-bottom: 16px;">
                                                        <tr>
                                                            <td style="width: 140px; vertical-align: top;">
                                                                <p
                                                                    style="margin: 0; color: #6b7280; font-size: 14px; font-weight: 500;">
                                                                    Tanggal Permintaan
                                                                </p>
                                                            </td>
                                                            <td style="vertical-align: top;">
                                                                <p
                                                                    style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 500;">
                                                                    {{ \Carbon\Carbon::parse($permintaan->created_at)->format('d M Y, H:i') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Divider -->
                                                    <div style="height: 1px; background: #e5e7eb; margin: 16px 0;">
                                                    </div>

                                                    <!-- Info Row 2 -->
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                        role="presentation" style="margin-bottom: 16px;">
                                                        <tr>
                                                            <td style="width: 140px; vertical-align: top;">
                                                                <p
                                                                    style="margin: 0; color: #6b7280; font-size: 14px; font-weight: 500;">
                                                                    Tanggal Selesai
                                                                </p>
                                                            </td>
                                                            <td style="vertical-align: top;">
                                                                <p
                                                                    style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 500;">
                                                                    {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Divider -->
                                                    <div style="height: 1px; background: #e5e7eb; margin: 16px 0;">
                                                    </div>

                                                    <!-- Info Row 3 -->
                                                    <table width="100%" cellpadding="0" cellspacing="0"
                                                        role="presentation">
                                                        <tr>
                                                            <td style="width: 140px; vertical-align: top;">
                                                                <p
                                                                    style="margin: 0; color: #6b7280; font-size: 14px; font-weight: 500;">
                                                                    Kode Transaksi
                                                                </p>
                                                            </td>
                                                            <td style="vertical-align: top;">
                                                                <p
                                                                    style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 500;">
                                                                    {{ $permintaan->pemilikData->kode_transaksi ?? '-' }}

                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>


                            <!-- Action Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                    <td class="button-padding" style="text-align: center; padding: 32px 0;">
                                        <table cellpadding="0" cellspacing="0" role="presentation"
                                            style="margin: 0 auto;">
                                            <tr>
                                                <td
                                                    style="background: #1e3a8a; border-radius: 6px; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
                                                    <a href="{{ url('/') }}" class="button-text"
                                                        style="display: inline-block; color: white; padding: 16px 32px; text-decoration: none; font-weight: 600; font-size: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                                        Lihat Hasil Pemrosesan
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Tips -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 6px;">
                                <tr>
                                    <td style="padding: 16px; text-align: center;">
                                        <p
                                            style="margin: 0; color: #6b7280; font-size: 14px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                            <strong style="color: #1f2937;">Tip:</strong> Simpan halaman hasil untuk
                                            referensi di kemudian hari
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer-padding"
                            style="background: #f8f9fa; padding: 32px; text-align: center; border-top: 1px solid #e9ecef;">
                            <p
                                style="margin: 0; color: #6b7280; font-size: 14px; margin-bottom: 16px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                Email ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.
                            </p>
                            <div style="border-top: 1px solid #e9ecef; padding-top: 16px;">
                                <p
                                    style="margin: 0; font-size: 12px; color: #9ca3af; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                                    © {{ date('Y') }} Badan Pusat Statistik - Kepulauan Bangka Belitung
                                </p>
                            </div>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
