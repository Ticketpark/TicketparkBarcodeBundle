<?php

namespace Ticketpark\BarcodeBundle\Tests\Generator;

use Ticketpark\BarcodeBundle\Generator\BarcodeGenerator;

class BarcodeGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->testImagePath  =  __DIR__.'/../../Test/Files/barcode';
        $this->getInstance();
    }

    public function getInstance($fileInCache=false)
    {
        $this->barcodeGenerator = new BarcodeGenerator($this->getFileHandlerMock($fileInCache));
    }

    public function testCreateBarcode()
    {
        $this->assertFalse(file_exists($this->testImagePath));
        $this->assertEquals($this->testImagePath, $this->barcodeGenerator->get('ABC123'));
        $this->assertTrue(file_exists($this->testImagePath));
        $this->assertTrue($this->isPng($this->testImagePath));

        unlink($this->testImagePath);
    }

    public function testCreateBarcodeInCache()
    {
        $this->getInstance(true);
        $this->assertEquals('oldFileFromCache', $this->barcodeGenerator->get('ABC123'));
    }

    public function getFileHandlerMock($fileInCache=false)
    {
        $fileHandler = $this->getMockBuilder('Ticketpark\FileBundle\FileHandler\FileHandler')
            ->disableOriginalConstructor()
            ->setMethods(array('fromCache', 'cache'))
            ->getMock();

        $fileHandler->expects($this->any())
            ->method('fromCache')
            ->will($this->returnValue(call_user_func(array($this, 'fromCache'), $fileInCache)));

        $fileHandler->expects($this->any())
            ->method('cache')
            ->will($this->returnValue($this->testImagePath));

        return $fileHandler;
    }

    public function fromCache($fileInCache)
    {
        if ($fileInCache) {
            return 'oldFileFromCache';
        }

        return false;
    }

    /**
     * @link http://codeaid.net/php/check-if-the-file-is-a-png-image-file-by-reading-its-signature
     */
    public function isPng($filename)
    {
        // check if the file exists
        if (!file_exists($filename)) {
            return false;
        }

        // define the array of first 8 png bytes
        $png_header = array(137, 80, 78, 71, 13, 10, 26, 10);
        // or: array(0x89, 0x50, 0x4E, 0x47, 0x0D, 0x0A, 0x1A, 0x0A);

        // open file for reading
        $f = fopen($filename, 'r');

        // read first 8 bytes from the file and close the resource
        $header = fread($f, 8);
        fclose($f);

        // convert the string to an array
        $chars = preg_split('//', $header, -1, PREG_SPLIT_NO_EMPTY);

        // convert each charater to its ascii value
        $chars = array_map('ord', $chars);

        // return true if there are no differences or false otherwise
        return (count(array_diff($png_header, $chars)) === 0);
    }
}
