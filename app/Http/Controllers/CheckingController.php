<?php

namespace App\Http\Controllers;

use App\Models\QrCodeModel;
use App\Services\QrCodeService;


class CheckingController extends Controller
{
    public function generateQrCode()
    {
        //TODO get the name of qr code from stripe webhook
        QrCodeService::create('123', 'app/public/qrCodes/make_me_qrcode.png');
    }

}
