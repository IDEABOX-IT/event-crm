<?php

namespace App\Http\Controllers;

use App\Services\QrCodeService;
use Illuminate\Support\Facades\Storage;

class CheckingController extends Controller
{
    public function generateQrCode()
    {
        // TODO FLOW
        // Receive a webhook from stripe
        // generate a QrCode with the webhook data
        // save the QrCode in the storage
        // sent email with the QrCode to customer
        // return response to stripe ?
        $qrCodePath = 'public/qrCodes/make_me_qrcode.png';
        $this->createQrCode('make_me_qrcode', $qrCodePath);
    }

    private function createQrCode(string $value, string $qrCodePath): void
    {
        QrCodeService::create($value, $qrCodePath);
    }

    public function checkQrCode(): string
    {
        // TODO FLOW
        // staff scan the QrCode
        // check if the QrCode is valid or not ( one time use and not expired )
        // return response to staff
        return 'ok';
    }

    public function getQrCode(): string
    {
        $qrCodePath = 'public/qrCodes/make_me_qrcode.png';

        if (Storage::exists($qrCodePath)) {
            return $this->getBase64QrCode($qrCodePath);
        }
        $this->createQrCode('make_me_qrcode', $qrCodePath);
        return $this->getBase64QrCode($qrCodePath);
    }

    // TODO maybe helper function ?

    private function getBase64QrCode(string $qrCodePath): string
    {
        $qrCodeImageContents = file_get_contents(storage_path('app/' . $qrCodePath));
        return $this->imageToBase64($qrCodeImageContents);
    }


    // TODO maybe helper function ?
    private function imageToBase64(string $imageData): string
    {
        $dataUrlPrefix = 'data:image/png;base64,';
        return $dataUrlPrefix . base64_encode($imageData);
    }

}
