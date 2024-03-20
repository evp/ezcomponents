<?php
/**
 * ezcConsoleQuestionDialogMappingValidatorTest class. 
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.6.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for ezcConsoleQuestionDialogMappingValidator class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleQuestionDialogMappingValidatorTest extends ezcTestCase
{
	public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcConsoleQuestionDialogMappingValidatorTest" );
    }

    public function testGetAccessDefaultSuccess()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator( $collection );
        $this->assertEquals( $collection, $validator->collection );
        $this->assertNull( $validator->default );
        $this->assertEquals( ezcConsoleQuestionDialogMappingValidator::CONVERT_NONE, $validator->conversion );
        $this->assertEquals( [], $validator->map );
    }

    public function testGetAccessCustomSuccess()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator(
            $collection,
            "foo",
            ezcConsoleQuestionDialogMappingValidator::CONVERT_UPPER,
            ['f' => 'foo']
        );
        $this->assertEquals( $collection, $validator->collection );
        $this->assertEquals( "foo", $validator->default );
        $this->assertEquals( ezcConsoleQuestionDialogMappingValidator::CONVERT_UPPER, $validator->conversion );
        $this->assertEquals( ['f' => 'foo'], $validator->map );
    }

    public function testGetAccessFailure()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator( $collection );
        
        try
        {
            echo $validator->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid property foo." );
    }

    public function testSetAccessSuccess()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator( $collection );

        $collectionNew         = [23, 42];
        $validator->collection = $collectionNew;
        $validator->default    = 23;
        $validator->conversion = ezcConsoleQuestionDialogMappingValidator::CONVERT_LOWER;
        $newMap                = ['f' => 23, 'g' => 42];
        $validator->map        = $newMap;

        $this->assertEquals( $collectionNew, $validator->collection );
        $this->assertEquals( 23, $validator->default );
        $this->assertEquals( ezcConsoleQuestionDialogMappingValidator::CONVERT_LOWER, $validator->conversion );
        $this->assertEquals( $newMap, $validator->map );
    }

    public function testSetAccessFailure()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator( $collection );
        
        $exceptionCaught = false;
        try
        {
            $validator->collection = true;
            $this->fail( "Exception not thrown on invalid value for property collection." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $validator->default = [];
            $this->fail( "Exception not thrown on invalid value for property default." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $validator->conversion = "Foo";
            $this->fail( "Exception not thrown on invalid value for property conversion." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        try
        {
            $validator->map = "Foo";
            $this->fail( "Exception not thrown on invalid value for property map." );
        }
        catch ( ezcBaseValueException $e )
        {
        }

        $exceptionCaught = false;
        try
        {
            $validator->foo = "Foo";
            $this->fail( "Exception not thrown on access of nonexistent property foo." );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
        }
    }

    public function testIssetAccess()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator( $collection );
        $this->assertTrue( isset( $validator->collection ), "Property collection not set." );
        $this->assertTrue( isset( $validator->default ), "Property default not set." );
        $this->assertTrue( isset( $validator->conversion ), "Property conversion not set." );
        $this->assertTrue( isset( $validator->map ), "Property map not set." );

        $this->assertFalse( isset( $validator->foo ), "Property foo set." );
    }

    public function testValidate()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator( $collection );
        $this->assertTrue( $validator->validate( "foo" ) );
        $this->assertFalse( $validator->validate( "test" ) );
    }

    public function testFixup()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator( $collection, null );

        $this->assertEquals( "foo", $validator->fixup( "foo" ), 'Fixup incorrect without conversion.' );
        $this->assertEquals( "FOO", $validator->fixup( "FOO" ), 'Fixup incorrect without conversion.' );

        $validator->conversion = ezcConsoleQuestionDialogMappingValidator::CONVERT_UPPER;
        
        $this->assertEquals( "FOO", $validator->fixup( "foo" ), 'Fixup incorrect with conversion to upper case.' );
        $this->assertEquals( "FOO", $validator->fixup( "FOO" ), 'Fixup incorrect with conversion to upper case.' );

        $validator->conversion = ezcConsoleQuestionDialogMappingValidator::CONVERT_LOWER;
        
        $this->assertEquals( "foo", $validator->fixup( "foo" ), 'Fixup incorrect with conversion to lower case.' );
        $this->assertEquals( "foo", $validator->fixup( "FOO" ), 'Fixup incorrect with conversion to lower case.' );

        $this->assertEquals( "", $validator->fixup( "" ) );

        $validator->default = "foo";

        $this->assertEquals( "foo", $validator->fixup( "" ) );
    }

    public function testFixupWithMapping()
    {
        $collection = ['y', 'n'];
        $validator = new ezcConsoleQuestionDialogMappingValidator(
            $collection,
            null,
            ezcConsoleQuestionDialogMappingValidator::CONVERT_NONE,
            ['yes' => 'y', 'no'  => 'n', '1'   => 'y', '0'   => 'n']
        );

        $this->assertEquals( "y", $validator->fixup( "yes" ) );
        $this->assertEquals( "y", $validator->fixup( "1" ) );
        $this->assertEquals( "YES", $validator->fixup( "YES" ) );
        
        $this->assertEquals( "n", $validator->fixup( "no" ) );
        $this->assertEquals( "n", $validator->fixup( "0" ) );
        $this->assertEquals( "NO", $validator->fixup( "NO" ) );

        $validator->conversion = ezcConsoleQuestionDialogMappingValidator::CONVERT_UPPER;

        $this->assertEquals( "YES", $validator->fixup( "yes" ) );
        $this->assertEquals( "y", $validator->fixup( "1" ) );
        $this->assertEquals( "YES", $validator->fixup( "YES" ) );
        
        $this->assertEquals( "NO", $validator->fixup( "no" ) );
        $this->assertEquals( "n", $validator->fixup( "0" ) );
        $this->assertEquals( "NO", $validator->fixup( "NO" ) );

        $validator->conversion = ezcConsoleQuestionDialogMappingValidator::CONVERT_LOWER;
        
        $this->assertEquals( "y", $validator->fixup( "yes" ) );
        $this->assertEquals( "y", $validator->fixup( "1" ) );
        $this->assertEquals( "y", $validator->fixup( "YES" ) );
        
        $this->assertEquals( "n", $validator->fixup( "no" ) );
        $this->assertEquals( "n", $validator->fixup( "0" ) );
        $this->assertEquals( "n", $validator->fixup( "NO" ) );

        $this->assertEquals( "", $validator->fixup( "" ) );

        $validator->default = "no";

        $this->assertEquals( "no", $validator->fixup( "" ) );
    }

    public function testGetResultString()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogMappingValidator(
            $collection,
            null,
            ezcConsoleQuestionDialogMappingValidator::CONVERT_NONE,
            ['f' => 'foo', 'b' => 'bar']
        );

        $this->assertEquals( "(foo/bar/baz)", $validator->getResultString() );

        $validator->default = "foo";

        $this->assertEquals( "(foo/bar/baz) [foo]", $validator->getResultString() );
    }
}

?>
