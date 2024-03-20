<?php

require_once 'tutorial_autoload.php';

$options = ['ttl'   => 30];

ezcCacheManager::createCache( 'array', '/tmp/cache/array', 'ezcCacheStorageFileArray', $options );

$exampleData = ['unique_id_3_a' => ['language' => 'en', 'section' => 'articles'], 'unique_id_3_b' => ['language' => 'de', 'section' => 'articles'], 'unique_id_3_c' => ['language' => 'no', 'section' => 'articles'], 'unique_id_3_d' => ['language' => 'de', 'section' => 'tutorials']];

$cache = ezcCacheManager::getCache( 'array' );

foreach ( $exampleData as $myId => $exampleDataArr )
{
    if ( ( $data = $cache->restore( $myId, $exampleDataArr ) ) === false )
    {
        $cache->store( $myId, $exampleDataArr, $exampleDataArr );
    }
}

echo "Data items with attribute <section> set to <articles>: " .
     $cache->countDataItems( null, ['section' => 'articles'] ) .
     "\n";
     
echo "Data items with attribute <language> set to <de>: " .
     $cache->countDataItems( null, ['language' => 'de'] ) .
     "\n\n";

$cache->delete( null, ['language' => 'de'] );

echo "Data items with attribute <section> set to <articles>: " .
     $cache->countDataItems( null, ['section' => 'articles'] ) .
     "\n";
     
echo "Data items with attribute <language> set to <de>: " .
     $cache->countDataItems( null, ['language' => 'de'] ) .
     "\n\n";

?>
