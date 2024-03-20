<?php
require_once 'tutorial_autoload.php';

$reader = new ezcTranslationTsBackend( __DIR__. '/translations' );
$reader->setOptions( ['format' => 'translation-[LOCALE].xml'] );
$reader->initReader( 'nb_NO' );

$cacheObj = new ezcCacheStorageFileArray( __DIR__. '/translations-cache' );
$writer = new ezcTranslationCacheBackend( $cacheObj );
$writer->initWriter( 'nb_NO' );

foreach ( $reader as $contextName => $contextData )
{
    $writer->storeContext( $contextName, $contextData );
}

$reader->deInitReader();
$writer->deInitWriter();
?>
