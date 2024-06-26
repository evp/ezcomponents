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
 * Test the struct ezcMvcInternalRedirect.
 *
 * @package MvcTools
 * @subpackage Tests
 */
class ezcMvcInternalRedirectTest extends ezcTestCase
{
    public function testIsStruct()
    {
        $struct = new ezcMvcInternalRedirect();
        $this->assertTrue( $struct instanceof ezcBaseStruct );
    }

    public function testGetSet()
    {
        $struct = new ezcMvcInternalRedirect();
        $struct->request = 'php';
        $this->assertEquals( 'php', $struct->request, 'Property request does not have the expected value' );
    }

    public function testSetState()
    {
        $state = ['request' => 'php'];
        $struct = ezcMvcInternalRedirect::__set_state( $state );
        $this->assertEquals( 'php', $struct->request, 'Property request does not have the expected value' );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcMvcInternalRedirectTest" );
    }
}
?>
