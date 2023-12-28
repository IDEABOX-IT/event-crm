<?php

namespace App\Http\Controllers;


use App\Mail\SendTicket;
use App\Models\Contact;
use App\Models\QrCode;
use App\Services\TicketService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

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

    public function checkTicket()
    {
        return Inertia::render('Events/QrCodeChecker');
    }

    public function checkTicketResult(Request $request, $qrCodeText)
    {
        $path = 'images/qrCodes/' . $qrCodeText . '.png';
        $qrCode = QrCode::where('qrCodePath', $path)->first();
        $contact = Contact::whereId($qrCode->contact_id)->first();
        $event = $qrCode->event;

        return Inertia::render('Events/QrCodeConfirm', [
            'contact' => $contact,
            'event' => $event,
            'qrCode' => $qrCode,
        ]);
    }

    public function confirmQrCode(QrCode $qrCode)
    {
        $qrCodeId = Request::get('id');
        $qrCode = QrCode::whereId($qrCodeId)->first();

        if($qrCode->isCheckinComplete){
            return Redirect::back()->with('error', 'check-in já foi realizado');
        }

        $qrCode->isCheckinComplete = true;
        $qrCode->save();

        return Inertia::render('Events/QrCodeChecker');

    }

}
