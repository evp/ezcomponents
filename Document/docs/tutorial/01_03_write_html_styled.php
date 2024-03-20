<?php

require 'tutorial_autoload.php';

$docbook = new ezcDocumentDocbook();
$docbook->loadFile( 'docbook.xml' );

$converter = new ezcDocumentDocbookToHtmlConverter();

// Remove the inline CSS
$converter->options->styleSheet = null;

// Add custom CSS style sheets
$converter->options->styleSheets = ['/styles/screen.css'];

$html = $converter->convert( $docbook );

echo $html->save();

?>
