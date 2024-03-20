<?php
/**
 * General example for the ImageConversion component.
 *
 * @package ImageConversion
 * @version 1.3.8
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'Base/src/base.php';
/**
 * Autoload ezc classes
 *
 * @param string $className
 */
function __autoload( $className )
{
    ezcBase::autoload( $className );
}

// Prepare settings for ezcImageConverter
// Defines the handlers to utilize and auto conversions.
$settings = new ezcImageConverterSettings(
    [new ezcImageHandlerSettings( 'GD',          'ezcImageGdHandler' ), new ezcImageHandlerSettings( 'ImageMagick', 'ezcImageImagemagickHandler' )],
    ['image/gif' => 'image/png', 'image/bmp' => 'image/jpeg']
);


// Create the converter itself.
$converter = new ezcImageConverter( $settings );

// Define a transformation
$filters = [new ezcImageFilter(
    'scaleWidth',
    ['width'     => 100, 'direction' => ezcImageGeometryFilters::SCALE_BOTH]
), new ezcImageFilter(
    'colorspace',
    ['space' => ezcImageColorspaceFilters::COLORSPACE_GREY]
)];

// Which MIME types the conversion may output
$mimeTypes = ['image/jpeg', 'image/png'];

// Create the transformation inside the manager
$converter->createTransformation( 'thumbnail', $filters, $mimeTypes );

// Transform an image.
$converter->transform( 'thumbnail', __DIR__. '/jpeg.jpg', __DIR__. '/jpeg_thumb.jpg' );

echo 'Succesfully converted <'. __DIR__. '/jpeg.jpg> to <'.__DIR__. '/jpeg_thumb.jpg'.">\n";
?>
