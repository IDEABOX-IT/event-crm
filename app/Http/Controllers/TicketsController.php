<?php

namespace App\Http\Controllers;


use App\Mail\SendTicket;
use App\Models\Contact;
use App\Models\QrCode;
use App\Services\TicketService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class TicketsController extends Controller
{
    public function send(Contact $contact, $quantity)
    {

        $generatedTicketPaths = TicketService::createTickets($contact, $quantity);

        $qrCodes = QrCode::whereIn('qrCodePath', $generatedTicketPaths)->get();

        Mail::to($contact->email)->send(new SendTicket($contact, '19:00', $qrCodes));

        return Redirect::back()->with('success', 'Ticket enviado corretamente');
    }

    public function resend(Contact $contact)
    {
        $qrCodes = QrCode::whereContactId($contact->id)->get();

        Mail::to($contact->email)->send(new SendTicket($contact, '19:00', $qrCodes));

        return Redirect::back()->with('success', 'Ticket enviado corretamente');
    }

}
