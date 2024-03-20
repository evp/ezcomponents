<?php
require 'tutorial_autoload.php';

$data = ezcBaseFile::findRecursive(
	"/dat/dev/ezcomponents",
	['@src/.*_autoload.php$@'],
	['@/autoload/@']
);
var_dump( $data );

?>
