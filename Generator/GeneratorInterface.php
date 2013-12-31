<?php

namespace Ticketpark\BarcodeBundle\Generator;

interface GeneratorInterface
{
    /**
     * Gets the barcode with the provided content
     *
     * @param  string $content  Content of the barcode
     * @param  array  $params   Array with individual layout parameters of this barcode. Will be merged with standard params.
     * @return string           Path to file of generated barcode
     */
    public function get($content, $params=array());
}