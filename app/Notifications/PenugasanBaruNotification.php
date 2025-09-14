<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
// app/Notifications/PenugasanBaruNotification.php

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class PenugasanBaruNotification extends Notification
{
    protected $permintaan;

    public function __construct($permintaan)
    {
        $this->permintaan = $permintaan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Penugasan Baru',
            'body' => 'Anda ditugaskan untuk permintaan ' . $this->permintaan->kode_transaksi,
            'url' => route('pengolah.index', $this->permintaan->id),
            'from' => Auth::user()->name, // ini akan tampil di dropdown
            'source' => 'permintaan_olah_data',
        ];
    }
}
