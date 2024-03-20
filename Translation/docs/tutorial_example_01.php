<?php
require_once 'tutorial_autoload.php';

$backend = new ezcTranslationTsBackend( __DIR__. '/translations' );
$backend->setOptions( ['format' => 'translation-[LOCALE].xml'] );

$manager = new ezcTranslationManager( $backend );
$headersContext = $manager->getContext( 'nb_NO', 'tutorial/headers' );
$descriptionContext = $manager->getContext( 'nb_NO', 'tutorial/descriptions' );

echo $headersContext->getTranslation( 'header1' ), "\n";
echo $descriptionContext->getTranslation( 'desc1' ), "\n";
echo $descriptionContext->getTranslation( 'desc2' ), "\n";

?>
