<?php

require_once 'tutorial_autoload.php';

$tutorialPath = __DIR__;

$settings = new ezcImageConverterSettings(
    [new ezcImageHandlerSettings( 'GD',          'ezcImageGdHandler' ), new ezcImageHandlerSettings( 'ImageMagick', 'ezcImageImagemagickHandler' )],
    ['image/gif' => 'image/png']
);

$converter = new ezcImageConverter( $settings );

$filters = [new ezcImageFilter(
    'watermarkAbsolute',
    ['image' => $tutorialPath . '/img/watermark.png', 'posX'  => -52, 'posY'  => -25]
)];

$converter->createTransformation( 'watermark', $filters, ['image/jpeg', 'image/png'] );

try
{
    $converter->transform( 
        'watermark', 
        $tutorialPath.'/img/imageconversion_example_04_before.jpg', 
        $tutorialPath.'/img/imageconversion_example_04_after.jpg' 
    );
}
catch ( ezcImageTransformationException $e)
{
    die( "Error transforming the image: <{$e->getMessage()}>" );
}

?>
