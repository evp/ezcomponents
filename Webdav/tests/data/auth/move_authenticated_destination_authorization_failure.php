<?php

return [['server' => [
    'REQUEST_URI'      => '/a/a1',
    'REQUEST_METHOD'   => 'MOVE',
    'HTTP_DESTINATION' => '/c/a1',
    // some:thing
    'HTTP_AUTHORIZATION' => 'Basic c29tZTp0aGluZw==',
], 'body' => ''], ['status' => 'HTTP/1.1 403 Forbidden', 'headers' => ['Server'           => 'eZComponents/dev/ezcWebdavTransportTestMock', 'Content-Type'     => 'text/plain; charset="utf-8"', 'Content-Length'   => '21'], 'body' => 'Authorization failed.']];

?>
