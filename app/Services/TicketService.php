<?php

namespace App\Services;

use App\Models\Contact;
use App\Models\QrCode;
use DateTime;

class TicketService
{

    static function createTickets(Contact $contact, int $quantity, int $event_id): array
    {
        $ticketList = [];

        for ($i = 0; $i < $quantity; $i++) {
            $currentDateTime = new DateTime();
            $currentDateTimeString = $currentDateTime->format('Y-m-d H:i:s');
            $encryptedContactId = md5($contact->id . $currentDateTimeString);

            $path = 'images/qrCodes/' . $encryptedContactId . '.png';

            QrCodeService::createPublicFolder($encryptedContactId, $path);

            QrCode::create([
                'contact_id' => $contact->id,
                'qrCodePath' => $path,
                'event_id' => $event_id,
                'isCheckinComplete' => false,
            ]);

            $ticketList[] = $path; // Adicione o nome do arquivo à lista
        }

        return $ticketList;
    }
}
