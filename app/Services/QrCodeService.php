<?php

namespace App\Services;

use App\Services\QrCodeLib\GDLibRenderer;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Common\Version;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Exception\InvalidArgumentException;
use BaconQrCode\Renderer\Color\Alpha;
use BaconQrCode\Renderer\Color\Rgb;
use BaconQrCode\Renderer\RendererStyle\EyeFill;
use BaconQrCode\Renderer\RendererStyle\Fill;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    private Version $version;
    private ErrorCorrectionLevel $errorCorrectionLevel;
    private int $size;

    public function __construct(
        Version              $version = null,
        ErrorCorrectionLevel $errorCorrectionLevel = null,
        int                  $size = 600
    )
    {
        $this->version = $version ?? Version::getVersionForNumber(5);
        $this->errorCorrectionLevel = $errorCorrectionLevel ?? ErrorCorrectionLevel::H();
        $this->size = $size;
    }

    public static function create(string $qrCodeText, string $qrCodePath): void
    {
        $qrCodeGenerator = new self();
        $renderedQrCode = $qrCodeGenerator->renderQrCode();
        $writtenQrCode = $qrCodeGenerator->writeQrCode($qrCodeText, $renderedQrCode);
        Storage::put($qrCodePath, $writtenQrCode);
    }

    public static function createPublicFolder(string $qrCodeText, string $qrCodePath): void
    {
        $qrCodeGenerator = new self();
        $renderedQrCode = $qrCodeGenerator->renderQrCode();
        $writtenQrCode = $qrCodeGenerator->writeQrCode($qrCodeText, $renderedQrCode);
        Storage::disk('root_public')->put($qrCodePath, $writtenQrCode);
    }

    public function renderQrCode(): GDLibRenderer
    {
        return new GDLibRenderer($this->size, 3, 'png', 3, $this->getQrCodeFill());
    }

    public function getQrCodeFill(): Fill
    {
        $backgroundColor = new Alpha(0, new Rgb(0, 0, 0));
        $foregroundColor = new Rgb(0, 0, 0);
        $topLeftEyeFill = new EyeFill(new Rgb(0, 0, 0), new Alpha(100, new Rgb(0, 0, 0)));
        $topRightEyeFill = new EyeFill(new Rgb(0, 0, 0), new Alpha(100, new Rgb(0, 0, 0)));
        $bottomLeftEyeFill = new EyeFill(new Rgb(0, 0, 0), new Alpha(100, new Rgb(0, 0, 0)));

        return Fill::withForegroundColor(
            $backgroundColor,
            $foregroundColor,
            $topLeftEyeFill,
            $topRightEyeFill,
            $bottomLeftEyeFill,
        );
    }

    public function writeQrCode(string $qrCodeText, $renderer): string
    {
        $writer = new Writer($renderer);

        return $writer->writeString(
            $qrCodeText,
            Encoder::DEFAULT_BYTE_MODE_ECODING,
            $this->errorCorrectionLevel,
            $this->version,
        );
    }

    /**
     *
     * generate qr code data
     *
     * @param string $qrCodeText
     * @return string
     * @throws InvalidArgumentException
     */
    public function data(string $qrCodeText): string
    {
        $qrCodeGenerator = $this;
        $renderedQrCode = $qrCodeGenerator->renderQrCode();
        return $qrCodeGenerator->writeQrCode($qrCodeText, $renderedQrCode);
    }
}
