<?php

namespace App\Mail;

use App\Models\PermintaanData;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PermintaanDataDiproses extends Mailable
{
    use Queueable, SerializesModels;

    public $permintaan;

    public function __construct(PermintaanData $permintaan)
    {
        $this->permintaan = $permintaan;
    }

    public function build()
    {
        return $this->subject('Permintaan Data Anda Sedang Diproses')
            ->view('emails.permintaan.permintaanProses') // atau ->markdown jika markdown
            ->with([
                'logoUrl' => asset('images/bps-logo.png'),
            ]);
    }
}
