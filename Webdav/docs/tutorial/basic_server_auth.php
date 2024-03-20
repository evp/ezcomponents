<?php

require_once 'tutorial_autoload.php';
require_once 'custom_auth.php';

$server = ezcWebdavServer::getInstance();

$server->auth = new myCustomAuthClass();

$backend = new ezcWebdavFileBackend(
   __DIR__ . '/backend'
);

$server->handle( $backend ); 

?>
