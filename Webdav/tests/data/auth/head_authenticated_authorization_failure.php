<?php

return [['server' => [
    'REQUEST_URI'        => '/a/a2',
    'REQUEST_METHOD'     => 'HEAD',
    // 23:42
    'HTTP_AUTHORIZATION' => 'Basic MjM6NDI=',
], 'body' => ''], ['status' => 'HTTP/1.1 403 Forbidden', 'headers' => ['Server'           => 'eZComponents/dev/ezcWebdavTransportTestMock', 'Content-Type'     => 'text/plain; charset="utf-8"', 'Content-Length'   => '21'], 'body' => 'Authorization failed.']];

?>
