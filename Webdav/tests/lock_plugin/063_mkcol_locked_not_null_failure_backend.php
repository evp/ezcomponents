<?php

$backendBefore = new ezcWebdavMemoryBackend();

$backendBefore->addContents(
    ['collection' => ['resource.html' => '', 'newcollection' => []]]
);

$backendBefore->setProperty(
    '/collection',
    new ezcWebdavLockDiscoveryProperty(
        new ArrayObject(
            [new ezcWebdavLockDiscoveryPropertyActiveLock(
                ezcWebdavLockRequest::TYPE_WRITE,
                ezcWebdavLockRequest::SCOPE_EXCLUSIVE,
                ezcWebdavRequest::DEPTH_ZERO,
                new ezcWebdavPotentialUriContent(
                    'http://example.com/some/user',
                    true
                ),
                604800,
                new ezcWebdavPotentialUriContent(
                    'opaquelocktoken:5678',
                    true
                ),
                null,
                new ezcWebdavDateTime()
            )]
        )
    )
);

$backendBefore->setProperty(
    '/collection/newcollection',
    new ezcWebdavLockDiscoveryProperty(
        new ArrayObject(
            [new ezcWebdavLockDiscoveryPropertyActiveLock(
                ezcWebdavLockRequest::TYPE_WRITE,
                ezcWebdavLockRequest::SCOPE_EXCLUSIVE,
                ezcWebdavRequest::DEPTH_INFINITY,
                new ezcWebdavPotentialUriContent(
                    'http://example.com/some/user',
                    true
                ),
                604800,
                new ezcWebdavPotentialUriContent(
                    'opaquelocktoken:1234',
                    true
                ),
                null,
                new ezcWebdavDateTime()
            )]
        )
    )
);

return $backendBefore;

?>
