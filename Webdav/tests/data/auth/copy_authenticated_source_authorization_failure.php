<?php

return [['server' => [
    'REQUEST_URI'      => '/b/b2',
    'REQUEST_METHOD'   => 'COPY',
    'HTTP_DESTINATION' => '/b/b1/bnew',
    // 23:42
    'HTTP_AUTHORIZATION' => 'Basic MjM6NDI=',
], 'body' => ''], ['status' => 'HTTP/1.1 403 Forbidden', 'headers' => ['Server'           => 'eZComponents/dev/ezcWebdavTransportTestMock', 'Content-Type'     => 'text/plain; charset="utf-8"', 'Content-Length'   => '21'], 'body' => 'Authorization failed.']];

?>
