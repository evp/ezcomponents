<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.3
 * @filesource
 * @package MvcTools
 * @subpackage Tests
 */

/**
 * Test the struct ezcMvcRoutingInformation.
 *
 * @package MvcTools
 * @subpackage Tests
 */
class ezcMvcRoutingInformationTest extends ezcTestCase
{
    public function testIsStruct()
    {
        $struct = new ezcMvcRoutingInformation();
        $this->assertTrue( $struct instanceof ezcBaseStruct );
    }

    public function testGetSet()
    {
        $struct = new ezcMvcRoutingInformation();
        $struct->matchedRoute = 'php';
        $this->assertEquals( 'php', $struct->matchedRoute, 'Property matchedRoute does not have the expected value' );
        $struct->controllerClass = 'ezc';
        $this->assertEquals( 'ezc', $struct->controllerClass, 'Property controllerClass does not have the expected value' );
        $struct->action = 'ezp';
        $this->assertEquals( 'ezp', $struct->action, 'Property action does not have the expected value' );
    }

    public function testSetState()
    {
        $state = ['matchedRoute' => 'php', 'controllerClass' => 'ezc', 'action' => 'ezp', 'router' => null];
        $struct = ezcMvcRoutingInformation::__set_state( $state );
        $this->assertEquals( 'php', $struct->matchedRoute, 'Property matchedRoute does not have the expected value' );
        $this->assertEquals( 'ezc', $struct->controllerClass, 'Property controllerClass does not have the expected value' );
        $this->assertEquals( 'ezp', $struct->action, 'Property action does not have the expected value' );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcMvcRoutingInformationTest" );
    }
}
?>
