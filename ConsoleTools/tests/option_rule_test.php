<?php
/**
 * ezcConsoleOptionRuleTest class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.6.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for ezcConsoleOption class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleOptionRuleTest extends ezcTestCase
{

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcConsoleOptionRuleTest" );
	}

    public function testGetAccessSuccess()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a", "b", "c"] );

        $this->assertSame( $option, $rule->option );
        $this->assertEquals( ["a", "b", "c"], $rule->values );
        $this->assertTrue( $rule->ifSet );
    }

    public function testGetAccessFailure()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a", "b", "c"] );

        try
        {
            $foo = $rule->nonExistent;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return true;
        }
        $this->fail( "Exception not thrown on access of invalid property." );
    }
    
    public function testSetAccessSuccess()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a"] );

        $rule->option = $option;
        $rule->values = ["a", "b", "c"];
        $rule->ifSet  = false;

        $this->assertSame( $option, $rule->option );
        $this->assertEquals( ["a", "b", "c"], $rule->values );
        $this->assertFalse( $rule->ifSet );
    }

    public function testSetAccessFailureOption()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a"] );

        try
        {
            $rule->option = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOptionRule->option." );
    }

    public function testSetAccessFailureValues()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a"] );

        try
        {
            $rule->values = 23;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOptionRule->values." );
    }

    public function testSetAccessFailureIfSet()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a"] );

        try
        {
            $rule->ifSet = 23;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOptionRule->ifSet." );
    }

    public function testSetAccessFailureNonExsitent()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a"] );

        try
        {
            $rule->nonExistent = 23;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOptionRule->nonExistent." );
    }

    public function testIssetAccessSuccess()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a"] );

        $this->assertTrue( isset( $rule->option ) );
        $this->assertTrue( isset( $rule->values ) );
        $this->assertTrue( isset( $rule->ifSet ) );
    }

    public function testIssetAccessFailure()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $rule = new ezcConsoleOptionRule( $option, ["a"] );

        $this->assertFalse( isset( $rule->nonExsistent ) );
    }
}

?>
