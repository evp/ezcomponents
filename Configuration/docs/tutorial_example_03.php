<?php
require_once 'tutorial_autoload.php';

$writer = new ezcConfigurationArrayWriter();
$writer->init( __DIR__, "settings", $cfg );
$writer->save();
?>
