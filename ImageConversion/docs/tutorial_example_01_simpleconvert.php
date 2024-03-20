<?php

require_once 'tutorial_autoload.php';

$tutorialPath = __DIR__;

$settings = new ezcImageConverterSettings(
    [new ezcImageHandlerSettings( 'GD',          'ezcImageGdHandler' ), new ezcImageHandlerSettings( 'ImageMagick', 'ezcImageImagemagickHandler' )]
);

$converter = new ezcImageConverter( $settings );

$converter->createTransformation( 'jpeg', [], ['image/jpeg'] );

try
{
    $converter->transform( 
        'jpeg', 
        $tutorialPath.'/img/imageconversion_example_01_before.bmp', 
        $tutorialPath.'/img/imageconversion_example_01_after.jpg' 
    );
}
catch ( ezcImageTransformationException $e)
{
    die( "Error transforming the image: <{$e->getMessage()}>" );
}


?>
