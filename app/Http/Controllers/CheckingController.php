<?php

namespace App\Http\Controllers;

use App\Models\QrCodeModel;


class CheckingController extends Controller
{
    public function generateQrCode()
    {
        QrCodeModel::generateQrCode('email or phone number from client ??? ');
    }

}
