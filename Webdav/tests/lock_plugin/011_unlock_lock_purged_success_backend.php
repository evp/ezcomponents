<?php

$backendBefore = new ezcWebdavMemoryBackend();

$backendBefore->addContents(
    ['collection' => ['resource.html' => '']]
);

$backendBefore->setProperty(
    '/collection',
    new ezcWebdavLockDiscoveryProperty()
);

$backendBefore->setProperty(
    '/collection/resource.html',
    new ezcWebdavLockDiscoveryProperty()
);

return $backendBefore;

?>
