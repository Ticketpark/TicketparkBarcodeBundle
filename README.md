# TicketparkBarcodeBundle

This Symfony2 bundle ads functionalities to create barcodes and qr codes

## Functionalities
* Barcode Generator (Service and TwigExtension)
* QR Code Generator (Service and TwigExtension)

## Installation

Add TicketparkBarcodeBundle in your composer.json:

```js
{
    "require": {
        "ticketpark/barcode-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update ticketpark/barcode-bundle
```

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ticketpark\BarcodeBundle\TicketparkBarcodeBundle(),
    );
}
```
## Usage of Barcode / QR Code Generator
Use the barcode generator service in a controller to create barcodes and qr codes:

``` php
// Create barcodes
$barcodeGenerator = $this->get('ticketpark.barcode.barcode');
$pathToBarcodeImage = $barcodeGenerator->get('someString');

// Create qr codes
$qrCodeGenerator = $this->get('ticketpark.barcode.qr');
$pathToQrCodeImage = $qrCodeGenerator->get('someString');
```


There is also a Twig extension, examples:
``` html
<img src="{{ someString|barcode|base64 }}">
<img src="{{ someString|qrcode|base64 }}">
```

**Advanced Usage**:<br>
You send manipulate the generated barcode with additional parameters.
See the Generator files in the `Generator` directory for more information about available types and options.

``` php
// Create barcodes
$barcodeGenerator = $this->get('ticketpark.barcode.barcode');
$params = array(
    'type' => 'code39',
    'barcodeOptions' => array(
        'barHeight' => 80
    )
);
$pathToBarcodeImage = $barcodeGenerator->get('someString', $params);
```

## License
This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
