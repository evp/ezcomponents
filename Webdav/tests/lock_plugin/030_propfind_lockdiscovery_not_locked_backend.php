<?php

$backendBefore = new ezcWebdavMemoryBackend();

$backendBefore->addContents(
    ['collection' => ['resource.html' => '']]
);

return $backendBefore;

?>
