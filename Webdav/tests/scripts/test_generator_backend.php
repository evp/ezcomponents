<?php

$backend = new ezcWebdavMemoryBackend( true );
$backend->addContents(
    ['collection' => ['file.txt'  => 'Some text content.', 'subdir' => ['file.html' => '<html><body><h1>Test</h1></body></html>', 'file.xml' => "<?xml?>\n<content/>"]], 'secure_collection' => ['file.txt'  => 'Some text content.', 'subdir' => ['file.html' => '<html><body><h1>Test</h1></body></html>', 'file.xml' => "<?xml?>\n<content/>"]], 'file.xml' => "<?xml ?>\n<content/>", 'file.bin' => "\0§\"$%&"]
);

$backend->setProperty(
    '/collection/file.txt',
    new ezcWebdavGetContentTypeProperty(
        'text/plain', 'utf-8'
    )
);
$backend->setProperty(
    '/collection/subdir/file.html',
    new ezcWebdavGetContentTypeProperty(
        'text/html', 'utf-8'
    )
);
$backend->setProperty(
    '/collection/subdir/file.xml',
    new ezcWebdavGetContentTypeProperty(
        'text/xml', 'utf-8'
    )
);
$backend->setProperty(
    '/file.xml',
    new ezcWebdavGetContentTypeProperty(
        'text/xml', 'utf-8'
    )
);
$backend->setProperty(
    '/file.bin',
    new ezcWebdavGetContentTypeProperty(
        'application/octet-stream', 'utf-8'
    )
);

$backend->setProperty(
    '/secure_collection/file.txt',
    new ezcWebdavGetContentTypeProperty(
        'text/plain', 'utf-8'
    )
);
$backend->setProperty(
    '/secure_collection/subdir/file.html',
    new ezcWebdavGetContentTypeProperty(
        'text/html', 'utf-8'
    )
);
$backend->setProperty(
    '/secure_collection/subdir/file.xml',
    new ezcWebdavGetContentTypeProperty(
        'text/xml', 'utf-8'
    )
);

return $backend;

?>
