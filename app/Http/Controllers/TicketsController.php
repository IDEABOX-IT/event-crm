<?php

namespace App\Http\Controllers;


use App\Mail\SendTicket;
use App\Models\Contact;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class TicketsController extends Controller
{
    public function sendTicket(Contact $contact)
    {
        $targetUser = $contact;

        Mail::to('rijoedi@gmail.com')->send(new SendTicket($targetUser->first_name, '19:00', $targetUser->qrCodes));

        return Redirect::back()->with('success', 'Ticket enviado corretamente');
    }

}
