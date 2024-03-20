<?php

$backend = new ezcWebdavMemoryBackend();

$backend->addContents(
    ['collection' => ['resource.html' => '']]
);

return $backend;

?>
