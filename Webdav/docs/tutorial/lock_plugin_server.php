<?php

require_once 'tutorial_autoload.php';

require_once 'custom_lock_auth.php';

$server = ezcWebdavServer::getInstance();

$server->auth = new myCustomLockAuth(
    // Some configuration directory here
    __DIR__ . '/tokens.php'
);

$server->pluginRegistry->registerPlugin(
    new ezcWebdavLockPluginConfiguration()
);

$backend = new ezcWebdavFileBackend(
    // Your WebDAV directory here
    __DIR__ . '/backend'
);

$server->handle( $backend );

?>
