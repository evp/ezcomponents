<?php

require_once 'tutorial_autoload.php';

$tutorialPath = __DIR__;

$settings = new ezcImageConverterSettings(
    [new ezcImageHandlerSettings( 'GD',          'ezcImageGdHandler' ), new ezcImageHandlerSettings( 'ImageMagick', 'ezcImageImagemagickHandler' )]
);

$converter = new ezcImageConverter( $settings );

$filters = [new ezcImageFilter( 
    'scale',
    ['width'     => 320, 'height'    => 240, 'direction' => ezcImageGeometryFilters::SCALE_DOWN]
)];

$converter->createTransformation( 'preview', $filters, ['image/jpeg'] );

try
{
    $converter->transform( 
        'preview', 
        $tutorialPath.'/img/imageconversion_example_02_before.jpg', 
        $tutorialPath.'/img/imageconversion_example_02_after.jpg' 
    );
}
catch ( ezcImageTransformationException $e)
{
    die( "Error transforming the image: <{$e->getMessage()}>" );
}

?>
