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
 * Test the struct ezcMvcFilterDefinition.
 *
 * @package MvcTools
 * @subpackage Tests
 */
class ezcMvcFilterDefinitionTest extends ezcTestCase
{
    public function testIsStruct()
    {
        $struct = new ezcMvcFilterDefinition();
        $this->assertTrue( $struct instanceof ezcBaseStruct );
    }

    public function testGetSet()
    {
        $struct = new ezcMvcFilterDefinition();
        $struct->className = 'php';
        $this->assertEquals( 'php', $struct->className, 'Property className does not have the expected value' );
        $struct->options = 'ezc';
        $this->assertEquals( 'ezc', $struct->options, 'Property options does not have the expected value' );
    }

    public function testSetState()
    {
        $state = ['className' => 'php', 'options' => 'ezc'];
        $struct = ezcMvcFilterDefinition::__set_state( $state );
        $this->assertEquals( 'php', $struct->className, 'Property className does not have the expected value' );
        $this->assertEquals( 'ezc', $struct->options, 'Property options does not have the expected value' );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcMvcFilterDefinitionTest" );
    }
}
?>
