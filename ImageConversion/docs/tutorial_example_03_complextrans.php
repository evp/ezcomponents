<?php

require_once 'tutorial_autoload.php';

$tutorialPath = __DIR__;

$settings = new ezcImageConverterSettings(
    [new ezcImageHandlerSettings( 'GD',          'ezcImageGdHandler' ), new ezcImageHandlerSettings( 'ImageMagick', 'ezcImageImagemagickHandler' )],
    ['image/gif' => 'image/png']
);

$converter = new ezcImageConverter( $settings );

$filters = [new ezcImageFilter( 
    'scale',
    ['width'     => 320, 'height'    => 240, 'direction' => ezcImageGeometryFilters::SCALE_DOWN]
), new ezcImageFilter( 
    'colorspace',
    ['space' => ezcImageColorspaceFilters::COLORSPACE_GREY]
), new ezcImageFilter( 
    'border',
    ['width' => 5, 'color' => [240, 240, 240]]
)];

$converter->createTransformation( 'oldphoto', $filters, ['image/jpeg', 'image/png'] );

try
{
    $converter->transform( 
        'oldphoto', 
        $tutorialPath.'/img/imageconversion_example_03_before.jpg', 
        $tutorialPath.'/img/imageconversion_example_03_after.jpg' 
    );
}
catch ( ezcImageTransformationException $e)
{
    die( "Error transforming the image: <{$e->getMessage()}>" );
}

?>
