<?php
class testOneTemplateView extends ezcMvcView
{
    function createZones( $layout )
    {
        $zones = [];
        $zones[] = new ezcMvcTemplateViewHandler( 'page_layout', __DIR__ . '/views/template/simple.ezt' ); 
        return $zones;
    }
}

class testTwoTemplateViews extends ezcMvcView
{
    function createZones( $layout )
    {
        $zones = [];
        $zones[] = new ezcMvcTemplateViewHandler( 'nav', __DIR__ . '/views/template/nav.ezt' ); 
        $zones[] = new ezcMvcTemplateViewHandler( 'page_layout', __DIR__ . '/views/template/simple_with_nav.ezt' ); 
        return $zones;
    }
}

class testNonExistingTemplateView extends ezcMvcView
{
    function createZones( $layout )
    {
        $zones = [];
        $zones[] = new ezcMvcTemplateViewHandler( 'page_layout', __DIR__ . '/views/template/not_here.ezt' ); 
        return $zones;
    }
}
?>
