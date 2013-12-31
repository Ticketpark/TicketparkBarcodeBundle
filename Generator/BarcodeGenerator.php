<?php

namespace Ticketpark\BarcodeBundle\Generator;

use Ticketpark\FileBundle\FileHandler\FileHandlerInterface;
use Zend\Barcode\Barcode;

class BarcodeGenerator implements GeneratorInterface
{
    public function __construct(FileHandlerInterface $fileHandler)
    {
        $this->fileHandler  = $fileHandler;
    }

    /**
     * Gets the barcode with the provided content
     *
     * @param  string $content  Content of the barcode
     * @param  array  $params   Array with individual layout parameters of this barcode. Will be merged with standard params.
     * @return string           Path to file of generated barcode
     */
    public function get($content, $params=array())
    {
        $params   =  array_merge($this->getStandardParams(), $params);
        $filename = $this->getFilename($content, $params);

        if (!$file = $this->fileHandler->fromCache($filename)) {
            $params['barcodeOptions']['text'] =  $content;

            //We are doing a dirty trick with the cache here in order to save the file in cache without much hassle
            $file = $this->fileHandler->cache('null', $filename);

            $barcode = Barcode::draw($params['type'], 'image', $params['barcodeOptions']);
            imagepng($barcode, $file);
            imagedestroy($barcode);
        }

        return $file;
    }

    /**
     * Returns the filename of the file to be generated
     *
     * @param  string $content  Content of code
     * @param  array  $params   Array with individual layout parameters of this qr code
     * @return string           Designated file path of file to be generated
     */
    protected function getFilename($content, $params)
    {
        $filename = hash('sha256', 'barcode_'.$content.serialize($params)).'.png';

        return $filename;
    }

    /**
     * Returns standard params
     *
     * Documentation:
     * type:           http://framework.zend.com/manual/2.1/en/modules/zend.barcode.objects.html#description-of-shipped-barcodes
     * barcodeOptions: http://framework.zend.com/manual/2.1/en/modules/zend.barcode.objects.html#common-options
     *
     * @return array
     */
    protected function getStandardParams()
    {
        return array(
            'type' => 'code39',
            'barcodeOptions' => array(
                'barHeight'      => 80,
                'barThickWidth'  => 4,
                'barThinWidth'   => 2,
                'drawText'       => false,
                'withQuietZones' => false
            )
        );
    }
}