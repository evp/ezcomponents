<?php

$backendBefore = new ezcWebdavMemoryBackend();

$backendBefore->addContents(
    ['collection' => ['resource.html' => ''], 'other_collection' => []]
);

return $backendBefore;

?>
