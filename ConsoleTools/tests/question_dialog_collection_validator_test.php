<?php
/**
 * ezcConsoleQuestionDialogCollectionValidatorTest class. 
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.6.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for ezcConsoleQuestionDialogCollectionValidator class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleQuestionDialogCollectionValidatorTest extends ezcTestCase
{
	public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( "ezcConsoleQuestionDialogCollectionValidatorTest" );
    }

    public function testGetAccessDefaultSuccess()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection );
        $this->assertEquals( $collection, $validator->collection );
        $this->assertNull( $validator->default );
        $this->assertEquals( ezcConsoleQuestionDialogCollectionValidator::CONVERT_NONE, $validator->conversion );
    }

    public function testGetAccessCustomSuccess()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator(
            $collection,
            "foo",
            ezcConsoleQuestionDialogCollectionValidator::CONVERT_UPPER
        );
        $this->assertEquals( $collection, $validator->collection );
        $this->assertEquals( "foo", $validator->default );
        $this->assertEquals( ezcConsoleQuestionDialogCollectionValidator::CONVERT_UPPER, $validator->conversion );
    }

    public function testGetAccessFailure()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection );
        
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
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection );

        $collectionNew = [23, 42];
        $validator->collection = $collectionNew;
        $validator->default = 23;
        $validator->conversion = ezcConsoleQuestionDialogCollectionValidator::CONVERT_LOWER;

        $this->assertEquals( $collectionNew, $validator->collection );
        $this->assertEquals( 23, $validator->default );
        $this->assertEquals( ezcConsoleQuestionDialogCollectionValidator::CONVERT_LOWER, $validator->conversion );
    }

    public function testSetAccessFailure()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection );
        
        $exceptionCaught = false;
        try
        {
            $validator->collection = true;
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionCaught = true;
        }
        $this->assertTrue( $exceptionCaught, "Exception not thrown on invalid value for property collection." );

        $exceptionCaught = false;
        try
        {
            $validator->default = [];
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionCaught = true;
        }
        $this->assertTrue( $exceptionCaught, "Exception not thrown on invalid value for property default." );

        $exceptionCaught = false;
        try
        {
            $validator->conversion = "Foo";
        }
        catch ( ezcBaseValueException $e )
        {
            $exceptionCaught = true;
        }
        $this->assertTrue( $exceptionCaught, "Exception not thrown on invalid value for property conversion." );

        $exceptionCaught = false;
        try
        {
            $validator->foo = "Foo";
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            $exceptionCaught = true;
        }
        $this->assertTrue( $exceptionCaught, "Exception not thrown on access of nonexistent property foo." );
    }

    public function testIssetAccess()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection );
        $this->assertTrue( isset( $validator->collection ), "Property collection not set." );
        $this->assertTrue( isset( $validator->default ), "Property default not set." );
        $this->assertTrue( isset( $validator->conversion ), "Property conversion not set." );

        $this->assertFalse( isset( $validator->foo ), "Property foo set." );
    }

    public function testValidate()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection );
        $this->assertTrue( $validator->validate( "foo" ) );
        $this->assertFalse( $validator->validate( "test" ) );
    }

    public function testFixup()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection, null );

        $this->assertEquals( "foo", $validator->fixup( "foo" ) );
        $this->assertEquals( "FOO", $validator->fixup( "FOO" ) );

        $validator->conversion = ezcConsoleQuestionDialogCollectionValidator::CONVERT_UPPER;
        
        $this->assertEquals( "FOO", $validator->fixup( "foo" ) );
        $this->assertEquals( "FOO", $validator->fixup( "FOO" ) );

        $validator->conversion = ezcConsoleQuestionDialogCollectionValidator::CONVERT_LOWER;
        
        $this->assertEquals( "foo", $validator->fixup( "foo" ) );
        $this->assertEquals( "foo", $validator->fixup( "FOO" ) );

        $this->assertEquals( "", $validator->fixup( "" ) );

        $validator->default = "foo";

        $this->assertEquals( "foo", $validator->fixup( "" ) );
    }

    public function testGetResultString()
    {
        $collection = ["foo", "bar", "baz"];
        $validator = new ezcConsoleQuestionDialogCollectionValidator( $collection, null );

        $this->assertEquals( "(foo/bar/baz)", $validator->getResultString() );

        $validator->default = "foo";

        $this->assertEquals( "(foo/bar/baz) [foo]", $validator->getResultString() );
    }
}

?>
