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
    public function send(Contact $contact, $quantity, $event_id)
    {

        $generatedTicketPaths = TicketService::createTickets($contact, $quantity, $event_id);

        $qrCodes = QrCode::whereIn('qrCodePath', $generatedTicketPaths)->with(['event'])->get();
        $event = $qrCodes->first()->event;

        Mail::to($contact->email)->send(new SendTicket($contact, $event->time, $qrCodes));

        return Redirect::back()->with('success', 'Ticket enviado corretamente');
    }

    public function resend(Contact $contact)
    {
        $qrCodes = QrCode::whereContactId($contact->id)->with(['event'])->get();

        $events = $qrCodes->map(function ($qrCode) {
            return $qrCode->event;
        });

        $qrCodesByEvents = $events->map(function ($event) use ($qrCodes) {
            return $qrCodes->where('event_id', $event->id);
        });

        $qrCodesByEvents = $qrCodesByEvents->unique();

        foreach ($qrCodesByEvents as $qrCodes) {
            Mail::to($contact->email)->send(new SendTicket($contact, $qrCodes->first()->event->time, $qrCodes));
        }

        return Redirect::back()->with('success', 'Ticket enviado corretamente');
    }

}
