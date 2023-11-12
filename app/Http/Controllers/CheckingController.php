<?php

namespace App\Http\Controllers;

use App\Enumerations\LogLevelEnum;
use App\Logging\LogMaker;
use App\Models\QrCodeModel;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CheckingController extends Controller
{
    public function generateQrCode()
    {
        $qrCodePath = 'public/qrCodes/make_me_qrcode.png';
        $this->createQrCode('make_me_qrcode', $qrCodePath);
    }

    public function getQrCode(): string
    {
        //
        $qrCodePath = 'public/qrCodes/make_me_qrcode.png';

        if (Storage::exists($qrCodePath)) {
            return $this->getBase64QrCode($qrCodePath);
        }
        $this->createQrCode('make_me_qrcode', $qrCodePath);
        return $this->getBase64QrCode($qrCodePath);
    }

    private function createQrCode(string $value, string $qrCodePath): void
    {
        QrCodeService::create($value, $qrCodePath);
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
