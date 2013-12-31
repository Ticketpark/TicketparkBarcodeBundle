<?php

namespace Ticketpark\BarcodeBundle\Twig;

use Ticketpark\BarcodeBundle\Generator\BarcodeGenerator;
use Ticketpark\BarcodeBundle\Generator\QrCodeGenerator;

class BarcodeExtension extends \Twig_Extension
{
    public function __construct(BarcodeGenerator $barcodeGenerator, QrCodeGenerator $qrcodeGenerator)
    {
        $this->barcodeGenerator = $barcodeGenerator;
        $this->qrcodeGenerator  = $qrcodeGenerator;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('barcode', array($this, 'getBarcode')),
            new \Twig_SimpleFilter('qrcode',  array($this, 'getQrcode')),
        );
    }

    /**
     * Creates a barcode
     *
     * @param $code
     * @return string
     */
    public function getBarcode($code, $params=array())
    {
        return $this->barcodeGenerator->get($code, $params);
    }

    /**
     * Creates a QR code
     *
     * @param $code
     * @return string
     */
    public function getQrcode($code, $params=array())
    {
        return $this->qrcodeGenerator->get($code, $params);
    }

    public function getName()
    {
        return 'ticketpark_barcode_extension';
    }
}