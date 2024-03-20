<?php

require_once 'tutorial_autoload.php';

$tutorialPath = __DIR__;

$settings = new ezcImageConverterSettings(
    [new ezcImageHandlerSettings( 'GD',          'ezcImageGdHandler' ), new ezcImageHandlerSettings( 'ImageMagick', 'ezcImageImagemagickHandler' )],
    ['image/gif' => 'image/png']
);

$converter = new ezcImageConverter( $settings );

$filters = [new ezcImageFilter(
    'filledThumbnail',
    ['width'  => 100, 'height' => 100, 'color'  => [200, 200, 200]]
)];

$converter->createTransformation( 'thumbnail', $filters, ['image/jpeg', 'image/png'] );

try
{
    $converter->transform( 
        'thumbnail', 
        $tutorialPath.'/img/imageconversion_example_05_before.jpg', 
        $tutorialPath.'/img/imageconversion_example_05_after.jpg' 
    );
}
catch ( ezcImageTransformationException $e)
{
    die( "Error transforming the image: <{$e->getMessage()}>" );
}

?>
