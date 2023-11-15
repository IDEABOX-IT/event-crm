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
    public function __construct($client_name, $time, $qrCodesPath)
    {
        $this->client_name = $client_name;
        $this->time = $time;
        $this->$qrCodesPath = $qrCodesPath;
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
                'client_name' => $this->client_name,
                'time' => $this->time,
                'qrCodes' => $this->$qrCodesPath,
            ])
            ->subject('【' . config('app.name') . '】チケットを送信しました');
    }
}
