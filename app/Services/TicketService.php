<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\QrCode;
use Faker\Provider\File;

class TicketService
{

    static function createTicket(Contact $contact, int $quantity): void
    {
        $encryptedContactId = md5($contact->id . $contact->created_at);

        $path = 'images/qrCodes/' . $encryptedContactId  . '.png';

        QrCodeService::createPublicFolder($encryptedContactId, $path);

        QrCode::create([
            'contact_id' => $contact->id,
            'qrCodePath' => $path,
            'isCheckinComplete' => false,
        ]);

    }
}
