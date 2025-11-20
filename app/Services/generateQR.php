<?php

namespace App\Services;

// Using Endroid QrCode library to generate QR codes
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;

/**
 * Class generateQR.
 */
class generateQR
{
    public function hendle($text, $label, $path, $fileName)
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $text,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: public_path('assets/sq-logo.png'),
            logoResizeToWidth: 50,
            logoPunchoutBackground: true,
            labelText: $label,
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center
        );
        $result = $builder->build();
        // Directly output the QR code
        header('Content-Type: '.$result->getMimeType());

        // Save it to a file
        $destinationPath = $path;
        $namaQR = $fileName;
        $result->saveToFile($destinationPath.$namaQR);
    }
}
