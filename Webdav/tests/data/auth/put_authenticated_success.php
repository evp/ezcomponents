<?php

return [['server' => [
    'REQUEST_URI'         => '/b/b2',
    'REQUEST_METHOD' => 'PUT',
    // foo:bar
    'HTTP_AUTHORIZATION'  => 'Basic Zm9vOmJhcg==',
    'CONTENT_TYPE'   => 'text/plain; charset="utf-8"',
    'HTTP_CONTENT_LENGTH' => '9',
], 'body' => 'Some text'], ['status' => 'HTTP/1.1 201 Created', 'headers' => ['ETag'           => 'faa2d7660c5937353a5a954b9780da8a', 'Server'         => 'eZComponents/dev/ezcWebdavTransportTestMock', 'Content-Length' => '0'], 'body' => '']];

?>
