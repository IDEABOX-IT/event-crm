<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class SendTicket extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $time, $qrCodePaths)
    {
        $this->client = $client;
        $this->time = $time;
        $this->qrCodePaths = $qrCodePaths;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.tickets.sendTicket')
            ->with([
                'client' => $this->client,
                'time' => $this->time,
                'qrCodePaths' => $this->qrCodePaths,
            ])
            ->subject('【' . config('app.name') . '】チケットを送信しました');
    }
}
