<?php
/**
 * ezcConsoleArgumentTest class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.6.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for ezcConsoleArgument class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleArgumentTest extends ezcTestCase
{

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcConsoleArgumentTest" );
	}

    protected function setUp()
    {
    }

    public function testConstructorSuccess()
    {
        $arg = new ezcConsoleArgument(
            "testname",
            ezcConsoleInput::TYPE_INT,
            "Little short help",
            "Little long help",
            false,
            true,
            ["test"]
        );
        $this->assertEquals( "testname", $arg->name );
        $this->assertEquals( ezcConsoleInput::TYPE_INT, $arg->type );
        $this->assertEquals( "Little short help", $arg->shorthelp );
        $this->assertEquals( "Little long help", $arg->longhelp );
        $this->assertEquals( false, $arg->mandatory );
        $this->assertEquals( ["test"], $arg->default );
        $this->assertEquals( true, $arg->multiple );
    }

    public function testConstructorFailure()
    {
        $exceptionThrown = false;
        try
        {
            $arg = new ezcConsoleArgument( 23 );
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionThrown = true;
        }
        $this->assertTrue( $exceptionThrown, "ezcBaseValueException not thrown on invalid name." );

        $exceptionThrown = false;
        try
        {
            $arg = new ezcConsoleArgument( "" );
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionThrown = true;
        }
        $this->assertTrue( $exceptionThrown, "ezcBaseValueException not thrown on invalid name." );
    }

    public function testGetAccessSuccess()
    {
        $arg = new ezcConsoleArgument( "testname" );
        $this->assertEquals( "testname", $arg->name );
        $this->assertEquals( ezcConsoleInput::TYPE_STRING, $arg->type );
        $this->assertEquals( "No help available.", $arg->shorthelp );
        $this->assertEquals( "There is no help for this argument available.", $arg->longhelp );
        $this->assertEquals( true, $arg->mandatory );
        $this->assertEquals( false, $arg->multiple );
        $this->assertEquals( null, $arg->default );
        $this->assertEquals( null, $arg->value );
    }

    public function testGetAccessFailure()
    {
        $arg = new ezcConsoleArgument( "testname" );
        try
        {
            echo $arg->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "ezcBasePropertyNotFoundException not found on access to invalid property foo." );
    }

    public function testSetAccessSuccess()
    {
        $arg = new ezcConsoleArgument( "testname" );
        
        $this->assertSetProperty(
            $arg,
            "type",
            [ezcConsoleInput::TYPE_STRING, ezcConsoleInput::TYPE_INT],
            "ezcBaseValueException"
        );
        $this->assertSetProperty(
            $arg,
            "shorthelp",
            ["", "foo"],
            "ezcBaseValueException"
        );
        $this->assertSetProperty(
            $arg,
            "longhelp",
            ["", "foo"],
            "ezcBaseValueException"
        );
        $this->assertSetProperty(
            $arg,
            "mandatory",
            [true, false],
            "ezcBaseValueException"
        );
        $this->assertSetProperty(
            $arg,
            "multiple",
            [true, false],
            "ezcBaseValueException"
        );
        $this->assertSetProperty(
            $arg,
            "default",
            ["", "foo", ["foo", "bar"], null],
            "ezcBaseValueException"
        );
        $this->assertSetProperty(
            $arg,
            "value",
            ["", "foo", ["foo", "bar"], null],
            "ezcBaseValueException"
        );
    }

    public function testSetAccessFailure()
    {
        $arg = new ezcConsoleArgument( "testname" );

        $this->assertSetPropertyFails(
            $arg,
            "name",
            ["", "foo", 1, true, [], new stdClass()],
            "ezcBasePropertyPermissionException"
        );
        $this->assertSetPropertyFails(
            $arg,
            "type",
            ["", "foo", 23, true, [], new stdClass()],
            "ezcBaseValueException"
        );
        $this->assertSetPropertyFails(
            $arg,
            "shorthelp",
            [23, true, [], new stdClass()],
            "ezcBaseValueException"
        );
        $this->assertSetPropertyFails(
            $arg,
            "longhelp",
            [23, true, [], new stdClass()],
            "ezcBaseValueException"
        );
        $this->assertSetPropertyFails(
            $arg,
            "mandatory",
            ["", "foo", 23, [], new stdClass()],
            "ezcBaseValueException"
        );
        $this->assertSetPropertyFails(
            $arg,
            "multiple",
            ["", "foo", 23, [], new stdClass()],
            "ezcBaseValueException"
        );
        $this->assertSetPropertyFails(
            $arg,
            "default",
            [new stdClass()],
            "ezcBaseValueException"
        );
        $this->assertSetPropertyFails(
            $arg,
            "value",
            [new stdClass()],
            "ezcBaseValueException"
        );

        $this->assertSetPropertyFails(
            $arg,
            "foo",
            ["foo"],
            "ezcBasePropertyNotFoundException"
        );
    }
}
?>
