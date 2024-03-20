<?php
require_once 'tutorial_autoload.php';

$backend = new ezcTranslationTsBackend( __DIR__. '/translations' );
$backend->setOptions( ['format' => 'translation-[LOCALE].xml'] );

$backend->initReader( 'nb_NO' );

foreach ( $backend as $contextName => $contextData )
{
    echo $contextName, "\n";
    foreach ( $contextData as $context )
    {
        echo "\toriginal string:   {$context->original}\n";
        echo "\ttranslated string: {$context->translation}\n\n";
    }
}
?>
