<?php
require __DIR__ . '/../../../../Base/src/base.php';
function __autoload( $className )
{
    ezcBase::autoload( $className );
}
$parser = new ezcMailParser();

$set = new ezcMailFileSet( ['php://stdin'] );

$mail = $parser->parseMail( $set );

echo $mail[0]->from, "\n";
echo $mail[0]->subject, "\n";
?>
