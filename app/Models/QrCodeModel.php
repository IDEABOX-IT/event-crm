<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeModel
{
    static function generateQrCode($text)
    {
        $directory = storage_path('app/public/qrCodes');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = $directory . '/make_me_qrcode.png';

        $maxRetries = 3;
        $retryDelay = 2; // 2 seconds between retries

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                // TODO get the contents from stripe webhook
                $qrCode = QrCode::generate($text);

                if (file_put_contents($filePath, $qrCode) === false) {
                    throw new \Exception("Failed to save the QR code file.");
                }
                return response()->json(['message' => 'QR code generated and saved successfully']);
            } catch (\Exception $e) {
                Log::error($e->getMessage());

                if ($attempt < $maxRetries) {
                    sleep($retryDelay);
                } else {
                    return response()->json(['error' => 'QR code generation failed after multiple attempts. Please try again later.'], 500);
                }
            }
        }
    }
}
