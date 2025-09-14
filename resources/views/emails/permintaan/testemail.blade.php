@component('mail::message')
    # Halo, {{ $nama }}! 👋

    Ini adalah email uji coba dari Laravel.
    Jika kamu menerima ini, berarti konfigurasi berhasil!

    @component('mail::button', ['url' => 'https://laravel.com'])
        Kunjungi Laravel
    @endcomponent

    Terima kasih,<br>
    {{ config('app.name') }}
@endcomponent
