<?php

$backendBefore = new ezcWebdavMemoryBackend();

$backendBefore->addContents(
    ['collection' => ['resource.html' => "Some content.\n"]]
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
                    'opaquelocktoken:1234',
                    true
                ),
                null,
                new ezcWebdavDateTime()
            )]
        )
    )
);

$backendBefore->setProperty(
    '/collection/resource.html',
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
                    'opaquelocktoken:1234',
                    true
                ),
                '/collection'
            )]
        )
    )
);


return $backendBefore;

?>
