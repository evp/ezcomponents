<?php
class helloTestView extends ezcMvcView
{
    function createZones( $layout )
    {
        $zones = [];
        $zones[] = new ezcMvcPhpViewHandler( 'content', '../templates/downloadTest.php' );
        return $zones;
    }
}
?>
