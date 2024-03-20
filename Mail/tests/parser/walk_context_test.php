<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @filesource
 * @package Mail
 * @subpackage Tests
 */

/**
 * @package Mail
 * @subpackage Tests
 */
class ezcMailPartWalkContextTest extends ezcTestCase
{
    public function testProperties()
    {
        $context = new ezcMailPartWalkContext( ['WalkContextTestApp', 'saveMailPart'] );
        $context->includeDigests = true;
        $context->level = 3;
        $context->filter = ['ezcMailFile'];
        $this->assertEquals( true, $context->includeDigests );
        $this->assertEquals( 3, $context->level );
        $this->assertEquals( ['ezcMailFile'], $context->filter );
    }

    public function testPropertiesInvalid()
    {
        $context = new ezcMailPartWalkContext( ['WalkContextTestApp', 'saveMailPart'] );
        try
        {
            $context->no_such_property = true;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
        }

        try
        {
            $context->level = -1;
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $context->includeDigests = "yes";
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $test = $context->no_such_property;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
        }
    }

    public function testIsSet()
    {
        $context = new ezcMailPartWalkContext( ['WalkContextTestApp', 'saveMailPart'] );
        $this->assertEquals( true, isset( $context->includeDigests ) );
        $this->assertEquals( true, isset( $context->filter ) );
        $this->assertEquals( true, isset( $context->level ) );
        $this->assertEquals( true, isset( $context->callbackFunction ) );
        $this->assertEquals( false, isset( $context->no_such_property ) );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcMailPartWalkContextTest" );
    }
}

/**
 * Test class.
 */
class WalkContextTestApp
{
    public static function saveMailPart()
    {
    }
}
?>
