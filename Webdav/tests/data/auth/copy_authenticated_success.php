<?php

return [['server' => [
    'REQUEST_URI'      => '/a/a2',
    'REQUEST_METHOD'   => 'COPY',
    'HTTP_DESTINATION' => '/a/a1/bnew',
    // some:thing
    'HTTP_AUTHORIZATION' => 'Basic c29tZTp0aGluZw==',
], 'body' => ''], ['status' => 'HTTP/1.1 201 Created', 'headers' => ['Server'         => 'eZComponents/dev/ezcWebdavTransportTestMock', 'Content-Length' => '0'], 'body' => '']];

?>
