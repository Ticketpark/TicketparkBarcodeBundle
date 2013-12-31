<?php

namespace Ticketpark\BarcodeBundle\Generator;

use PHPQRCode\QRcode;
use Ticketpark\FileBundle\FileHandler\FileHandlerInterface;

class QrCodeGenerator implements GeneratorInterface
{
    public function __construct(FileHandlerInterface $fileHandler)
    {
        $this->fileHandler  = $fileHandler;
    }

    /**
     * Gets the qr code with the provided content
     *
     * @param  string $content  Content of the qr code
     * @param  array  $params   Array with individual layout parameters of this qr code. Will be merged with standard params.
     * @return string           File path of generated qr code
     */
    public function get($content, $params=array())
    {
        $params =  array_merge($this->getStandardParams(), $params);
        $filename = $this->getFilename($content, $params);

        if (!$file = $this->fileHandler->fromCache($filename)) {

            //We are doing a dirty trick with the cache here in order to save the file in cache without much hassle
            $file = $this->fileHandler->cache('null', $filename);
            QRcode::png($content, $file, $params['eccLevel'], $params['size'], $params['frame']);
        }

        return $file;
    }

    /**
     * Returns the filename to be generated
     *
     * @param  string $content  Content of code
     * @param  array  $params   Array with individual layout parameters of this qr code
     * @return string           Designated file path of file to be generated
     */
    protected function getFilename($content, $params)
    {
        $filename = hash('sha256', 'qr_'.$content.serialize($params)).'.png';

        return $filename;
    }

    /**
     * Returns standard params
     *
     * Documentation:
     * eccLevel: http://phpqrcode.sourceforge.net/examples/index.php?example=006
     * size:     http://phpqrcode.sourceforge.net/examples/index.php?example=007
     * frame:    http://phpqrcode.sourceforge.net/examples/index.php?example=008
     *
     * @return array
     */
    protected function getStandardParams()
    {
        return array(
            'eccLevel' => 'L',
            'size'     => 4,
            'frame'    => 0
        );
    }
}