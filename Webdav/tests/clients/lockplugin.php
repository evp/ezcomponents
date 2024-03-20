<?php
return [1 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'LOCK', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_TIMEOUT' => 'Infinite, Second-4100000000', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:prop xmlns:D="DAV:">
  <D:lockdiscovery>
    <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:locktype>
        <D:write/>
      </D:locktype>
      <D:lockscope>
        <D:exclusive/>
      </D:lockscope>
      <D:depth>Infinity</D:depth>
      <D:owner>
        <D:href>http://example.com/some/user</D:href>
      </D:owner>
      <D:timeout>Second-604800</D:timeout>
      <D:locktoken>
        <D:href>opaquelocktoken:12345678-1234-1234-1234-123456789012</D:href>
      </D:locktoken>
      <ezclock:lastaccess xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">2008-11-09T22:14:18+00:00</ezclock:lastaccess>
    </D:activelock>
  </D:lockdiscovery>
</D:prop>
', 'headers' => 
['Lock-Token' => 'opaquelocktoken:12345678-1234-1234-1234-123456789012', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'text/xml; charset="utf-8"'], 'status' => 'HTTP/1.1 200 OK']], 2 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'LOCK', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_TIMEOUT' => 'Infinite, Second-4100000000', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:prop xmlns:D="DAV:">
  <D:lockdiscovery>
    <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:locktype>
        <D:write/>
      </D:locktype>
      <D:lockscope>
        <D:exclusive/>
      </D:lockscope>
      <D:depth>Infinity</D:depth>
      <D:owner>
        <D:href>http://example.com/some/user</D:href>
      </D:owner>
      <D:timeout>Second-604800</D:timeout>
      <D:locktoken>
        <D:href>opaquelocktoken:12345678-1234-1234-1234-123456789012</D:href>
      </D:locktoken>
      <ezclock:lastaccess xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">2008-11-09T22:14:18+00:00</ezclock:lastaccess>
    </D:activelock>
  </D:lockdiscovery>
</D:prop>
', 'headers' => 
['Lock-Token' => 'opaquelocktoken:12345678-1234-1234-1234-123456789012', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'text/xml; charset="utf-8"'], 'status' => 'HTTP/1.1 200 OK']], 3 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'LOCK', 'REQUEST_URI' => '/collection/newresource.xml', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_TIMEOUT' => 'Infinite, Second-4100000000', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:prop xmlns:D="DAV:">
  <D:lockdiscovery>
    <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:locktype>
        <D:write/>
      </D:locktype>
      <D:lockscope>
        <D:exclusive/>
      </D:lockscope>
      <D:depth>Infinity</D:depth>
      <D:owner>
        <D:href>http://example.com/some/user</D:href>
      </D:owner>
      <D:timeout>Second-604800</D:timeout>
      <D:locktoken>
        <D:href>opaquelocktoken:12345678-1234-1234-1234-123456789012</D:href>
      </D:locktoken>
      <ezclock:lastaccess xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">2008-11-09T22:14:18+00:00</ezclock:lastaccess>
    </D:activelock>
  </D:lockdiscovery>
</D:prop>
', 'headers' => 
['Lock-Token' => 'opaquelocktoken:12345678-1234-1234-1234-123456789012', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'text/xml; charset="utf-8"'], 'status' => 'HTTP/1.1 201 Created']], 4 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'LOCK', 'REQUEST_URI' => '/collection/newcollection', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_TIMEOUT' => 'Infinite, Second-4100000000', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:prop xmlns:D="DAV:">
  <D:lockdiscovery>
    <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:locktype>
        <D:write/>
      </D:locktype>
      <D:lockscope>
        <D:exclusive/>
      </D:lockscope>
      <D:depth>Infinity</D:depth>
      <D:owner>
        <D:href>http://example.com/some/user</D:href>
      </D:owner>
      <D:timeout>Second-604800</D:timeout>
      <D:locktoken>
        <D:href>opaquelocktoken:12345678-1234-1234-1234-123456789012</D:href>
      </D:locktoken>
      <ezclock:lastaccess xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">2008-11-09T22:14:18+00:00</ezclock:lastaccess>
    </D:activelock>
  </D:lockdiscovery>
</D:prop>
', 'headers' => 
['Lock-Token' => 'opaquelocktoken:12345678-1234-1234-1234-123456789012', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'text/xml; charset="utf-8"'], 'status' => 'HTTP/1.1 201 Created']], 5 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'LOCK', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_TIMEOUT' => 'Infinite, Second-4100000000', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked']], 6 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'LOCK', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_TIMEOUT' => 'Infinite, Second-4100000000', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked']], 7 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'LOCK', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_TIMEOUT' => 'Infinite, Second-4100000000', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked']], 8 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'UNLOCK', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_LOCK_TOKEN' => '<opaquelocktoken:1234>', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 9 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'UNLOCK', 'REQUEST_URI' => '/collection', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_LOCK_TOKEN' => '<opaquelocktoken:1234>', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 10 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'UNLOCK', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_LOCK_TOKEN' => '<opaquelocktoken:1234>', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 11 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'UNLOCK', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_LOCK_TOKEN' => '<opaquelocktoken:1234>', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 12 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'UNLOCK', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_LOCK_TOKEN' => '<opaquelocktoken:1234>', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 13 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'UNLOCK', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_LOCK_TOKEN' => '<opaquelocktoken:1234>', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 14 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MOVE', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'HTTP_IF' => '(<opaquelocktoken:1234>) (<opaquelocktoken:5678>)', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 15 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MOVE', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'HTTP_IF' => '<http://example.com/collection/resource.html> (<opaquelocktoken:1234>)', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 16 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MOVE', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'HTTP_IF' => '<http://example.com/other_collection/> (<opaquelocktoken:5678>)', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 17 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MOVE', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 18 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MOVE', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked']], 19 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MOVE', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked']], 20 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'COPY', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'HTTP_IF' => '(<opaquelocktoken:1234>) (<opaquelocktoken:5678>)', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 21 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'COPY', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'HTTP_IF' => '<http://example.com/collection/resource.html> (<opaquelocktoken:1234>)', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 22 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'COPY', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'HTTP_IF' => '<http://example.com/other_collection/> (<opaquelocktoken:5678>)', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 23 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'COPY', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => '0'], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 24 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'COPY', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked']], 25 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'COPY', 'REQUEST_URI' => '/collection/resource.html', 'HTTP_DESTINATION' => '/other_collection/moved_resource.html', 'HTTP_OVERWRITE' => 'F', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true, 'opaquelocktoken:5678' => true]]]], 30 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propfind xmlns:D="DAV:">
  <D:prop>
    <D:lockdiscovery/>
  </D:prop>
</D:propfind>
', 'server' => 
['REQUEST_METHOD' => 'PROPFIND', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_DEPTH' => 'infinity', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:multistatus xmlns:D="DAV:">
  <D:response>
    <D:href>http://example.com/collection/</D:href>
    <D:propstat>
      <D:prop>
        <D:lockdiscovery/>
      </D:prop>
      <D:status>HTTP/1.1 200 OK</D:status>
    </D:propstat>
  </D:response>
  <D:response>
    <D:href>http://example.com/collection/resource.html</D:href>
    <D:propstat>
      <D:prop>
        <D:lockdiscovery/>
      </D:prop>
      <D:status>HTTP/1.1 200 OK</D:status>
    </D:propstat>
  </D:response>
</D:multistatus>
', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'text/xml; charset="utf-8"'], 'status' => 'HTTP/1.1 207 Multi-Status']], 31 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propfind xmlns:D="DAV:">
  <D:prop>
    <D:lockdiscovery/>
  </D:prop>
</D:propfind>
', 'server' => 
['REQUEST_METHOD' => 'PROPFIND', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_DEPTH' => 'infinity', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:multistatus xmlns:D="DAV:">
  <D:response xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
    <D:href>http://example.com/collection/</D:href>
    <D:propstat xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:prop>
        <D:lockdiscovery xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
          <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
            <D:locktype>
              <D:write/>
            </D:locktype>
            <D:lockscope>
              <D:exclusive/>
            </D:lockscope>
            <D:depth>Infinity</D:depth>
            <D:owner>
              <D:href>http://example.com/some/user</D:href>
            </D:owner>
            <D:timeout>Second-604800</D:timeout>
            <D:locktoken>
              <D:href>opaquelocktoken:1234</D:href>
            </D:locktoken>
            <ezclock:lastaccess xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">2008-11-09T22:14:18+00:00</ezclock:lastaccess>
          </D:activelock>
        </D:lockdiscovery>
      </D:prop>
      <D:status>HTTP/1.1 200 OK</D:status>
    </D:propstat>
  </D:response>
  <D:response xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
    <D:href>http://example.com/collection/resource.html</D:href>
    <D:propstat xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:prop>
        <D:lockdiscovery xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
          <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
            <D:locktype>
              <D:write/>
            </D:locktype>
            <D:lockscope>
              <D:exclusive/>
            </D:lockscope>
            <D:depth>Infinity</D:depth>
            <D:owner>
              <D:href>http://example.com/some/user</D:href>
            </D:owner>
            <D:timeout>Second-604800</D:timeout>
            <D:locktoken>
              <D:href>opaquelocktoken:1234</D:href>
            </D:locktoken>
            <ezclock:baseuri xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">/collection</ezclock:baseuri>
          </D:activelock>
        </D:lockdiscovery>
      </D:prop>
      <D:status>HTTP/1.1 200 OK</D:status>
    </D:propstat>
  </D:response>
</D:multistatus>
', 'headers' => 
['Content-Type' => 'text/xml; charset="utf-8"', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock'], 'status' => 'HTTP/1.1 207 Multi-Status']], 32 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propfind xmlns:D="DAV:">
  <D:prop>
    <D:lockdiscovery/>
  </D:prop>
</D:propfind>
', 'server' => 
['REQUEST_METHOD' => 'PROPFIND', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_DEPTH' => 'infinity', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:multistatus xmlns:D="DAV:">
  <D:response xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
    <D:href>http://example.com/collection/</D:href>
    <D:propstat xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:prop>
        <D:lockdiscovery xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
          <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
            <D:locktype>
              <D:write/>
            </D:locktype>
            <D:lockscope>
              <D:exclusive/>
            </D:lockscope>
            <D:depth>0</D:depth>
            <D:owner>
              <D:href>http://example.com/some/user</D:href>
            </D:owner>
            <D:timeout>Second-604800</D:timeout>
            <D:locktoken>
              <D:href>opaquelocktoken:1234</D:href>
            </D:locktoken>
            <ezclock:lastaccess xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">2008-11-09T22:14:18+00:00</ezclock:lastaccess>
          </D:activelock>
        </D:lockdiscovery>
      </D:prop>
      <D:status>HTTP/1.1 200 OK</D:status>
    </D:propstat>
  </D:response>
  <D:response>
    <D:href>http://example.com/collection/resource.html</D:href>
    <D:propstat>
      <D:prop>
        <D:lockdiscovery/>
      </D:prop>
      <D:status>HTTP/1.1 200 OK</D:status>
    </D:propstat>
  </D:response>
</D:multistatus>
', 'headers' => 
['Content-Type' => 'text/xml; charset="utf-8"', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock'], 'status' => 'HTTP/1.1 207 Multi-Status']], 33 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propfind xmlns:D="DAV:">
  <D:prop>
    <ezc:lockinfo xmlns:ezc="http://ezcomponents.org/s/Webdav#lock"/>
  </D:prop>
</D:propfind>
', 'server' => 
['REQUEST_METHOD' => 'PROPFIND', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_DEPTH' => 'infinity', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:multistatus xmlns:D="DAV:">
  <D:response xmlns:ezc="http://ezcomponents.org/s/Webdav#lock">
    <D:href>http://example.com/collection/</D:href>
    <D:propstat xmlns:ezc="http://ezcomponents.org/s/Webdav#lock">
      <D:prop>
        <ezc:lockinfo xmlns:ezc="http://ezcomponents.org/s/Webdav#lock"/>
      </D:prop>
      <D:status>HTTP/1.1 404 Not Found</D:status>
    </D:propstat>
  </D:response>
  <D:response xmlns:ezc="http://ezcomponents.org/s/Webdav#lock">
    <D:href>http://example.com/collection/resource.html</D:href>
    <D:propstat xmlns:ezc="http://ezcomponents.org/s/Webdav#lock">
      <D:prop>
        <ezc:lockinfo xmlns:ezc="http://ezcomponents.org/s/Webdav#lock"/>
      </D:prop>
      <D:status>HTTP/1.1 404 Not Found</D:status>
    </D:propstat>
  </D:response>
</D:multistatus>
', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'text/xml; charset="utf-8"'], 'status' => 'HTTP/1.1 207 Multi-Status']], 34 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propfind xmlns:D="DAV:">
  <D:prop>
    <ezclock:lockinfo xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock"/>
  </D:prop>
</D:propfind>
', 'server' => 
['REQUEST_METHOD' => 'PROPFIND', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_DEPTH' => 'infinity', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:multistatus xmlns:D="DAV:">
  <D:response xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
    <D:href>http://example.com/collection/</D:href>
    <D:propstat xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:prop>
        <ezclock:lockinfo xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock"/>
      </D:prop>
      <D:status>HTTP/1.1 404 Not Found</D:status>
    </D:propstat>
  </D:response>
  <D:response xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
    <D:href>http://example.com/collection/resource.html</D:href>
    <D:propstat xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
      <D:prop>
        <ezclock:lockinfo xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock"/>
      </D:prop>
      <D:status>HTTP/1.1 404 Not Found</D:status>
    </D:propstat>
  </D:response>
</D:multistatus>
', 'headers' => 
['Content-Type' => 'text/xml; charset="utf-8"', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock'], 'status' => 'HTTP/1.1 207 Multi-Status']], 40 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'DELETE', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 41 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'DELETE', 'REQUEST_URI' => '/collection/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 204 No Content'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 42 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'DELETE', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 204 No Content']], 50 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => 'Some content.
', 'headers' => 
['ETag' => 'f8fc7a3a8f8050e3f305dac66365e3ef', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'application/octet-stream; charset="utf-8"'], 'status' => 'HTTP/1.1 200 OK']], 51 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'GET', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_IF' => '(<opaquelocktoken:5678>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => 'Some content.
', 'headers' => 
['ETag' => 'f8fc7a3a8f8050e3f305dac66365e3ef', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'application/octet-stream; charset="utf-8"'], 'status' => 'HTTP/1.1 200 OK']], 55 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'HEAD', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['ETag' => 'f8fc7a3a8f8050e3f305dac66365e3ef', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'application/octet-stream; charset="utf-8"', 'Content-Length' => 0], 'status' => 'HTTP/1.1 200 OK']], 56 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'HEAD', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_IF' => '(<opaquelocktoken:5678>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['ETag' => 'f8fc7a3a8f8050e3f305dac66365e3ef', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Type' => 'application/octet-stream; charset="utf-8"', 'Content-Length' => 0], 'status' => 'HTTP/1.1 200 OK']], 60 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MKCOL', 'REQUEST_URI' => '/collection/newcollection', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 63 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MKCOL', 'REQUEST_URI' => '/collection/newcollection', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 405 Method Not Allowed'], 'failure' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 64 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MKCOL', 'REQUEST_URI' => '/collection/newcollection', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked'], 'failure' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 65 => 
['request' => 
['body' => '', 'server' => 
['REQUEST_METHOD' => 'MKCOL', 'REQUEST_URI' => '/collection/newcollection', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked'], 'failure' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 70 => 
['request' => 
['body' => 'Some content.
', 'server' => 
['REQUEST_METHOD' => 'PUT', 'REQUEST_URI' => '/collection/newresource', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'CONTENT_LENGTH' => '13', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['ETag' => '48c9fe7465ff389e8b0631b946d881f9', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 71 => 
['request' => 
['body' => 'Some content.
', 'server' => 
['REQUEST_METHOD' => 'PUT', 'REQUEST_URI' => '/collection/newresource', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'CONTENT_LENGTH' => '13', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['ETag' => '48c9fe7465ff389e8b0631b946d881f9', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 72 => 
['request' => 
['body' => 'Some content.
', 'server' => 
['REQUEST_METHOD' => 'PUT', 'REQUEST_URI' => '/collection/newresource', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'CONTENT_LENGTH' => '13', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['ETag' => '48c9fe7465ff389e8b0631b946d881f9', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 73 => 
['request' => 
['body' => 'Some content.
', 'server' => 
['REQUEST_METHOD' => 'PUT', 'REQUEST_URI' => '/collection/newresource', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'CONTENT_LENGTH' => '13', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['ETag' => '48c9fe7465ff389e8b0631b946d881f9', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 201 Created'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 74 => 
['request' => 
['body' => 'Some content.
', 'server' => 
['REQUEST_METHOD' => 'PUT', 'REQUEST_URI' => '/collection/newresource', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'CONTENT_LENGTH' => '13', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked'], 'failure' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 75 => 
['request' => 
['body' => 'Some content.
', 'server' => 
['REQUEST_METHOD' => 'PUT', 'REQUEST_URI' => '/collection/newresource', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'CONTENT_LENGTH' => '13', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked'], 'failure' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 80 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propertyupdate xmlns:D="DAV:"
xmlns:Z="http://www.w3.com/standards/z39.50/">
  <D:set>
       <D:prop>
            <Z:authors>
                 <Z:Author>Jim Whitehead</Z:Author>
                 <Z:Author>Roy Fielding</Z:Author>
            </Z:authors>
       </D:prop>
  </D:set>
</D:propertyupdate>
', 'server' => 
['REQUEST_METHOD' => 'PROPPATCH', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 200 OK'], 'success' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 81 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propertyupdate xmlns:D="DAV:"
xmlns:Z="http://www.w3.com/standards/z39.50/">
  <D:set>
       <D:prop>
            <Z:authors>
                 <Z:Author>Jim Whitehead</Z:Author>
                 <Z:Author>Roy Fielding</Z:Author>
            </Z:authors>
       </D:prop>
  </D:set>
</D:propertyupdate>
', 'server' => 
['REQUEST_METHOD' => 'PROPPATCH', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 423 Locked'], 'failure' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 82 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:propertyupdate xmlns:D="DAV:">
  <D:set>
       <D:prop>
            <D:lockdiscovery>
              <D:activelock xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">
                <D:locktype>
                  <D:write/>
                </D:locktype>
                <D:lockscope>
                  <D:exclusive/>
                </D:lockscope>
                <D:depth>Infinity</D:depth>
                <D:owner>
                  <D:href>http://example.com/some/different_user</D:href>
                </D:owner>
                <D:timeout>Second-604800</D:timeout>
                <D:locktoken>
                  <D:href>opaquelocktoken:9101</D:href>
                </D:locktoken>
                <ezclock:lastaccess xmlns:ezclock="http://ezcomponents.org/s/Webdav#lock">2008-11-09T22:14:18+00:00</ezclock:lastaccess>
              </D:activelock>
            </D:lockdiscovery>
       </D:prop>
  </D:set>
</D:propertyupdate>
', 'server' => 
['REQUEST_METHOD' => 'PROPPATCH', 'REQUEST_URI' => '/collection/resource.html', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_IF' => '(<opaquelocktoken:1234>)', 'CONTENT_TYPE' => 'text/xml; charset="utf-8"', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '<?xml version="1.0" encoding="UTF-8"?>
<D:multistatus xmlns:D="DAV:">
  <D:response>
    <D:href>http://example.com/collection/resource.html</D:href>
    <D:status>HTTP/1.1 409 Conflict</D:status>
    <D:responsedescription>Property \'lockdiscovery\' is readonly.</D:responsedescription>
  </D:response>
</D:multistatus>
', 'headers' => 
['Content-Type' => 'text/xml; charset="utf-8"', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock'], 'status' => 'HTTP/1.1 207 Multi-Status'], 'failure' => 
['tokens' => 
['foo' => 
['opaquelocktoken:1234' => true]]]], 100 => 
['request' => 
['body' => '<?xml version="1.0" encoding="utf-8" ?>
<D:lockinfo xmlns:D=\'DAV:\'>
  <D:lockscope><D:exclusive/></D:lockscope>
  <D:locktype><D:write/></D:locktype>
  <D:owner>
       <D:href>http://example.com/some/user</D:href>
  </D:owner>
</D:lockinfo>
', 'server' => 
['REQUEST_METHOD' => 'OPTIONS', 'REQUEST_URI' => '/', 'SERVER_PROTOCOL' => 'HTTP/1.1', 'HTTP_HOST' => 'example.com', 'HTTP_AUTHORIZATION' => 'Basic Zm9vOmJhcg==']], 'response' => 
['body' => '', 'headers' => 
['DAV' => '1, 2', 'Allow' => 'GET, HEAD, PROPFIND, PROPPATCH, OPTIONS, DELETE, COPY, MOVE, MKCOL, PUT, LOCK, UNLOCK', 'Server' => 'eZComponents/dev/ezcWebdavTransportMock', 'Content-Length' => 0], 'status' => 'HTTP/1.1 200 OK']]];
?>
