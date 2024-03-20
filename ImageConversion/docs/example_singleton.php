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

function getImageConverterInstance()
{
    if ( !isset( $GLOBALS['_ezcImageConverterInstance'] ) )
    {
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
            'scale',
            ['width'     => 100, 'height'    => 300, 'direction' => ezcImageGeometryFilters::SCALE_BOTH]
        ), new ezcImageFilter(
            'colorspace',
            ['space' => ezcImageColorspaceFilters::COLORSPACE_SEPIA]
        ), new ezcImageFilter(
            'border',
            ['width' => 5, 'color' => [255, 0, 0]]
        )];

        // Which MIME types the conversion may output
        $mimeTypes = ['image/jpeg', 'image/png'];

        // Create the transformation inside the manager
        $converter->createTransformation( 'funny', $filters, $mimeTypes );

        // Assign singleton instance
        $GLOBALS['_ezcImageConverterInstance'] = $converter;
    }

    // Return singleton instance
    return $GLOBALS['_ezcImageConverterInstance'];
}

// ...

// Somewhere else in the code...

// Transform an image.
getImageConverterInstance()->transform( 'funny', __DIR__.'/jpeg.jpg', __DIR__. '/jpeg_singleton.jpg' );

echo 'Succesfully converted <'. __DIR__. '/jpeg.jpg> to <'. __DIR__. '/jpeg_singleton.jpg'.">\n";
?>
