<?php
require_once 'tutorial_autoload.php';

$cfg = ezcConfigurationManager::getInstance();
$cfg->init( 'ezcConfigurationIniReader', __DIR__ . '/examples' );

$pw = $cfg->getSetting( 'settings', 'db', 'password' );
echo "The password is '{$pw}'.\n";

$settings = $cfg->getSettings( 'settings', 'db', ['user', 'password'] );
echo "Connecting with {$settings['user']}:{$settings['password']}.\n";

[$user, $pass] = $cfg->getSettingsAsList( 'settings', 'db', ['user', 'password'] );
echo "Connecting with {$user}:{$pass}.\n";
?>
