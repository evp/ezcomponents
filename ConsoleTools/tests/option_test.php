<?php
/**
 * ezcConsoleOptionTest
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
class ezcConsoleOptionTest extends ezcTestCase
{

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcConsoleOptionTest" );
	}

    public function testConstructionSuccess()
    {
        $optionDependency = new ezcConsoleOption( "d", "depend" );
        $optionExclusion = new ezcConsoleOption( "e", "exclude" );

        $ruleDependency = new ezcConsoleOptionRule( $optionDependency, ["abc"] );
        $ruleExclusion = new ezcConsoleOptionRule( $optionExclusion, ["abc"] );

        $option = new ezcConsoleOption(
            "a",
            "aaa",
            ezcConsoleInput::TYPE_INT,
            23,
            true,
            "Shorthelp",
            "Longhelp",
            [$ruleDependency],
            [$ruleExclusion],
            false,
            true,
            true
        );

        $this->assertEquals( $option->short, "a" );
        $this->assertEquals( $option->long, "aaa" );
        $this->assertEquals( ezcConsoleInput::TYPE_INT, $option->type );
        $this->assertEquals( 23, $option->default );
        $this->assertTrue( $option->multiple );
        $this->assertEquals( "Shorthelp", $option->shorthelp );
        $this->assertEquals( "Longhelp", $option->longhelp );
        $this->assertEquals( [$ruleDependency], $option->getDependencies() );
        $this->assertEquals( [$ruleExclusion], $option->getExclusions() );
        $this->assertFalse( $option->arguments );
        $this->assertTrue( $option->mandatory );
        $this->assertTrue( $option->isHelpOption );
    }

    public function testInvalidOptionName_short()
    {
        try
        {
            $option = new ezcConsoleOption( ' ', 'help' );
        }
        catch ( ezcConsoleInvalidOptionNameException $e )
        {
            return;
        }
        $this->fail( 'Exception not thrown on invalid option name.' );
    }
    
    public function testInvalidOptionName_long()
    {
        try
        {
            $option = new ezcConsoleOption( 'h', '--help' );
        }
        catch ( ezcConsoleInvalidOptionNameException $e )
        {
            return;
        }
        $this->fail( 'Exception not thrown on invalid option name.' );
    }
    

    public function testAddDependency()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addDependency( $rule );
        $option_1->addDependency( $rule );

        $this->assertAttributeEquals(
            [$rule],
            "dependencies",
            $option_1
        );
    }

    public function testRemoveDependency()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addDependency( $rule );
        $option_1->removeDependency( $rule );

        $this->assertAttributeEquals(
            [],
            "dependencies",
            $option_1
        );
    }

    // Bug #9052: Exception because of invalid property in ezcConsoleOption
    public function testRemoveAllDependencies()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule_1 = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $rule_2 = new ezcConsoleOptionRule(
            $option_2, ["d"]
        );

        $option_1->addDependency( $rule_1 );
        $option_1->addDependency( $rule_2 );
        $option_1->removeAllDependencies( $option_2 );

        $this->assertAttributeEquals(
            [],
            "dependencies",
            $option_1
        );
    }

    // Bug #9052: Exception because of invalid property in ezcConsoleOption
    public function testHasDependencySuccess()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addDependency( $rule );

        $this->assertTrue(
            $option_1->hasDependency( $option_2 )
        );
    }
    
    public function testHasDependencyFailure()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $this->assertFalse(
            $option_1->hasDependency( $option_2 )
        );
    }
    
    public function testResetDependencies()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addDependency( $rule );
        $option_1->resetDependencies();

        $this->assertAttributeEquals(
            [],
            "dependencies",
            $option_1
        );
    }
    
    public function testAddExclusion()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addExclusion( $rule );
        $option_1->addExclusion( $rule );

        $this->assertAttributeEquals(
            [$rule],
            "exclusions",
            $option_1
        );
    }

    public function testRemoveExclusion()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addExclusion( $rule );
        $option_1->removeExclusion( $rule );

        $this->assertAttributeEquals(
            [],
            "exclusions",
            $option_1
        );
    }

    // Bug #9052: Exception because of invalid property in ezcConsoleOption
    public function testRemoveAllExclusions()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule_1 = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $rule_2 = new ezcConsoleOptionRule(
            $option_2, ["d"]
        );

        $option_1->addExclusion( $rule_1 );
        $option_1->addExclusion( $rule_2 );
        $option_1->removeAllExclusions( $option_2 );

        $this->assertAttributeEquals(
            [],
            "exclusions",
            $option_1
        );
    }

    // Bug #9052: Exception because of invalid property in ezcConsoleOption
    public function testHasExclusionSuccess()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addExclusion( $rule );

        $this->assertTrue(
            $option_1->hasExclusion( $option_2 )
        );
    }
    
    public function testHasExclusionFailure()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $this->assertFalse(
            $option_1->hasExclusion( $option_2 )
        );
    }
    
    public function testResetExclusions()
    {
        $option_1 = new ezcConsoleOption(
            "a",
            "aaa"
        );
        $option_2 = new ezcConsoleOption(
            "b",
            "bbb"
        );

        $rule = new ezcConsoleOptionRule(
            $option_2, ["c"]
        );

        $option_1->addExclusion( $rule );
        $option_1->resetExclusions();

        $this->assertAttributeEquals(
            [],
            "exclusions",
            $option_1
        );
    }

    public function testPropertyGetAccessSuccess()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        $this->assertEquals( "a", $option->short );
        $this->assertEquals( "aaa", $option->long );
        $this->assertEquals( ezcConsoleInput::TYPE_NONE, $option->type );
        $this->assertNull( $option->default );
        $this->assertFalse( $option->multiple );
        $this->assertEquals( "No help available.", $option->shorthelp );
        $this->assertEquals( "Sorry, there is no help text available for this parameter.", $option->longhelp );
        $this->assertTrue( $option->arguments );
        $this->assertFalse( $option->mandatory );
        $this->assertFalse( $option->isHelpOption );
    }

    public function testPropertyGetAccessFailureDependencies()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $foo = $option->dependencies;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on access of ezcConsoleOption->dependencies." );
    }

    public function testPropertyGetAccessFailureExclusions()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $foo = $option->exclusions;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on access of ezcConsoleOption->exclusions." );
    }

    public function testPropertyGetAccessFailureNotExisting()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $foo = $option->nonExisting;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on access of ezcConsoleOption->nonExisting." );
    }

    public function testPropertySetAccessSuccess()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $option->type = ezcConsoleInput::TYPE_STRING;
        $option->type = ezcConsoleInput::TYPE_NONE;
        $option->type = ezcConsoleInput::TYPE_INT;
        $option->default = 10;
        $option->multiple = true;
        $option->shorthelp = "Shorthelp";
        $option->longhelp = "Longhelp";
        $option->arguments = false;
        $option->mandatory = true;
        $option->isHelpOption = true;

        $this->assertEquals( ezcConsoleInput::TYPE_INT, $option->type );
        $this->assertEquals( 10, $option->default );
        $this->assertTrue( $option->multiple );
        $this->assertEquals( "Shorthelp", $option->shorthelp );
        $this->assertEquals( "Longhelp", $option->longhelp );
        $this->assertFalse( $option->arguments );
        $this->assertTrue( $option->mandatory );
        $this->assertTrue( $option->isHelpOption );
    }

    public function testPropertySetAccessSuccessMultipleArray()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $option->multiple = true;
        $option->default = ["foo", "bar"];

        $this->assertEquals( ["foo", "bar"], $option->default );
    }

    public function testPropertySetAccessSuccessMultipleScalar()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $option->multiple = true;
        $option->default = 10;

        $this->assertEquals( 10, $option->default );
    }

    public function testPropertySetAccessFailureNoMultipleArray()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        $option->multiple = false;
        try
        {
            $option->default = ["foo", "bar"];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Assigning array when multiple is false worked." );
    }
    
    public function testPropertySetAccessFailureShort()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->short = "b";
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->short." );
    }

    public function testPropertySetAccessFailureLong()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->long = "bbb";
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->long." );
    }

    public function testPropertySetAccessFailureType()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->type = "Invalid type";
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->type." );
    }
    
    public function testPropertySetAccessFailureDefault()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->default = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->default." );
    }
    
    public function testPropertySetAccessFailureMultiple()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->multiple = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->multiple." );
    }
    
    public function testPropertySetAccessFailureShorthelp()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->shorthelp = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->shorthelp." );
    }

    public function testPropertySetAccessFailureLonghelp()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->longhelp = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->longhelp." );
    }

    public function testPropertySetAccessFailureArguments()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->arguments = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->arguments." );
    }

    public function testPropertySetAccessFailureMandatory()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->mandatory = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->mandatory." );
    }

    public function testPropertySetAccessFailureIsHelpOption()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->isHelpOption = [];
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->isHelpOption." );
    }
    
    public function testPropertySetAccessFailureNonExistent()
    {
        $option = new ezcConsoleOption( "a", "aaa" );

        try
        {
            $option->nonExistent = [];
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on invalid value for ezcConsoleOption->nonExsitent." );
    }

    public function testPropertyIssetAccessSuccess()
    {
        $option = new ezcConsoleOption( "a", "aaa" );
        
        $this->assertTrue( isset( $option->type ) );
        $this->assertFalse( isset( $option->default ) );
        $this->assertTrue( isset( $option->multiple ) );
        $this->assertTrue( isset( $option->shorthelp ) );
        $this->assertTrue( isset( $option->longhelp ) );
        $this->assertTrue( isset( $option->arguments ) );
        $this->assertTrue( isset( $option->mandatory ) );
        $this->assertTrue( isset( $option->isHelpOption ) );
        $this->assertFalse( isset(  $option->nonExistent ) );

    }
}

?>
