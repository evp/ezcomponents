<?php
class helloRouter extends ezcMvcRouter
{
    public function createRoutes()
    {
        return [new ezcMvcRailsRoute( '/downloadTest', 'helloTestController', 'download' ), new ezcMvcRailsRoute( '/:name', 'helloController', 'greetPersonally' ), new ezcMvcRailsRoute( '/', 'helloController', 'greet' )];
    }
}
?>
