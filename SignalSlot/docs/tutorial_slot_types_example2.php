<?php
require_once 'tutorial_autoload.php';

class HelloClass
{
    public function hello()
    {
        echo "Hello world\n";
    }
}

$signals = new ezcSignalCollection();
$signals->connect( "sayHello", [new HelloClass(), "hello"] );
$signals->emit( "sayHello" );
?>
