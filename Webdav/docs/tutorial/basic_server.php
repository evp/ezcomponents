<?php

require_once 'tutorial_autoload.php';

$server = ezcWebdavServer::getInstance();
$backend = new ezcWebdavFileBackend(
   __DIR__ . '/backend'
);

$server->handle( $backend ); 

?>
