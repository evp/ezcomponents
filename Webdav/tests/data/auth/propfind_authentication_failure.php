<?php

return [['server' => ['REQUEST_URI'        => '/a/a2', 'REQUEST_METHOD'     => 'PROPFIND', 'HTTP_AUTHORIZATION' => 'Basic c29tZTppbmNvcnJlY3Q='], 'body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propfind xmlns:D="DAV:">
  <D:allprop/>
</D:propfind>'], ['status' => 'HTTP/1.1 401 Unauthorized', 'headers' => ['WWW-Authenticate' => ['basic'  => 'Basic realm="eZ Components WebDAV"', 'digest' => 'Digest realm="eZ Components WebDAV", nonce="testnonce", algorithm="MD5"'], 'Server'           => 'eZComponents/dev/ezcWebdavTransportTestMock', 'Content-Type'     => 'text/plain; charset="utf-8"', 'Content-Length'   => '22'], 'body' => 'Authentication failed.']];

?>
