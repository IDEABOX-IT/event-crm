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
    public function __construct($client, $time, $qrCodes)
    {
        $this->client = $client;
        $this->time = $time;
        $this->qrCodes = $qrCodes;
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
                'qrCodes' => $this->qrCodes,
            ])
            ->subject('【' . config('app.name') . '】Ticket de acesso ao evento');
    }
}
