<?php
require_once 'tutorial_autoload.php';

$backend = new ezcTranslationTsBackend( __DIR__. '/translations' );
$backend->setOptions( ['format' => 'translation-[LOCALE].xml'] );

$manager = new ezcTranslationManager( $backend );
$manager->addFilter( ezcTranslationComplementEmptyFilter::getInstance() );
$headersContext = $manager->getContext( 'nl_NL', 'tutorial/headers' );
echo $headersContext->getTranslation( 'header1' ), "\n";
?>
