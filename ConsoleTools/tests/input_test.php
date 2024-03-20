<?php
/**
 * ezcConsoleInputTest class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 * @version 1.6.1
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for ezcConsoleInput class.
 * 
 * @package ConsoleTools
 * @subpackage Tests
 */
class ezcConsoleInputTest extends ezcTestCase
{
    private $testOptions = [['short'     => 't', 'long'      => 'testing', 'options'   => []], ['short'     => 's', 'long'      => 'subway', 'options'   => []], ['short'     => '', 'long'      => 'carry', 'options'   => []], ['short'     => 'v', 'long'      => 'visual', 'options'   => ['multiple'  => true, 'arguments' => false]], ['short'     => 'o', 'long'      => 'original', 'options'   => ['type'      => ezcConsoleInput::TYPE_STRING]], ['short'     => 'b', 'long'      => 'build', 'options'   => ['type'      => ezcConsoleInput::TYPE_INT, 'default'   => 42]], ['short'     => 'd', 'long'      => 'destroy', 'options'   => ['type'      => ezcConsoleInput::TYPE_STRING, 'default'   => 'world']], ['short'     => 'y', 'long'      => 'yank', 'options'   => ['type'          => ezcConsoleInput::TYPE_STRING, 'multiple'      => true, 'shorthelp'     => 'Some stupid short text.', 'longhelp'      => 'Some even more stupid, but somewhat longer long describtion.']], ['short'     => 'c', 'long'      => 'console', 'options'   => ['shorthelp'     => 'Some stupid short text.', 'longhelp'      => 'Some even more stupid, but somewhat longer long describtion.', 'depends'       => ['t', 'o', 'b', 'y']]], ['short'     => 'e', 'long'      => 'edit', 'options'   => ['excludes'      => ['t', 'y'], 'arguments'     => false]], ['short'     => 'n', 'long'      => 'new', 'options'   => ['depends'       => ['t', 'o'], 'excludes'      => ['b', 'y'], 'arguments'     => false]]];

    private $testAliasesSuccess = [['short' => 'k', 'long'  => 'kelvin', 'ref'   => 't'], ['short' => 'f', 'long'  => 'foobar', 'ref'   => 'o']];

    private $testAliasesFailure = [['short' => 'l', 'long'  => 'lurking', 'ref'   => 'x'], ['short' => 'e', 'long'  => 'elvis', 'ref'   => 'z'], ['short' => 'd', 'long'  => 'destroy', 'ref'   => 'd']];

    private $testArgsSuccess = [['foo.php', '-o', '"Test string2"', '--build', '42'], ['foo.php', '-b', '42', '--yank', '"a"', '--yank', '"b"', '--yank', '"c"'], ['foo.php', '--yank=a', '--yank=b', '--yank="c"', '-y', '1', '-y', '2'], ['foo.php', '--yank=a', '--yank=b', '-y', '1', 'arg1', 'arg2']];

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcConsoleInputTest" );
	}

    protected function setUp()
    {
        $this->input = new ezcConsoleInput();
        foreach ( $this->testOptions as $paramData )
        {
            $this->input->registerOption( $this->createFakeOption( $paramData ) );
        }
    }

    protected function createFakeOption( $paramData )
    {
        $param = new ezcConsoleOption( $paramData['short'], $paramData['long'] );
        foreach ( $paramData['options'] as $name => $val )
        {
            if ( $name === 'depends' )
            {
                foreach ( $val as $dep )
                {
                    $param->addDependency( new ezcConsoleOptionRule( $this->input->getOption( $dep ) ) );
                }
                continue;
            }
            if ( $name === 'excludes' )
            {
                foreach ( $val as $dep )
                {
                    $param->addExclusion(new ezcConsoleOptionRule( $this->input->getOption( $dep ) ) );
                }
                continue;
            }
            $param->$name = $val;
        }
        return $param;
    }

    protected function tearDown()
    {
        unset( $this->input );
    }

    public function testRegisterOptionSuccess()
    {
        $input = new ezcConsoleInput();
        foreach ( $this->testOptions as $optionData )
        {
            $option = $this->createFakeOption( $optionData );
            $input->registerOption( $option );
            if ( $option->short !== '' )
            {
                $this->assertEquals( 
                    $option,
                    $input->getOption( $optionData['short'] ),
                    'Parameter not registered correctly with short name <' . $optionData['short'] . '>.'
                );
            }
            $this->assertEquals( 
                $option,
                $input->getOption( $optionData['long'] ),
                'Parameter not registered correctly with long name <' . $optionData['long'] . '>.'
            );
        }
    }

    public function testRegisterOptionFailure()
    {
        $input = new ezcConsoleInput();
        foreach ( $this->testOptions as $optionData )
        {
            $option = $this->createFakeOption( $optionData );
            $input->registerOption( $option );
        }
        foreach ( $this->testOptions as $optionData )
        {
            $option = $this->createFakeOption( $optionData );
            $exceptionThrown = false;
            try
            {
                $input->registerOption( $option );
            }
            catch ( ezcConsoleOptionAlreadyRegisteredException $e )
            {
                $exceptionThrown = true;
            }
            $this->assertTrue(
                $exceptionThrown,
                "Exception not thrown on double registered option " . $optionData["short"] === "" ? "determined by long name." : "determined by short name."
            );
        }
    }

    public function testUnregisterOptionSuccess()
    {
        // register aliases for testing
        $validParams = [];
        foreach ( $this->input->getOptions() as $param )
        {
            $validParams[$param->short] = $param;
        }
        foreach ( $this->testAliasesSuccess as $alias )
        {
            $this->input->registerAlias( $alias['short'], $alias['long'], $validParams[$alias['ref']]  );
        }

        // test itself
        foreach ( $this->input->getOptions() as $option )
        {
            $this->input->unregisterOption( $option );
            $exceptionThrown = false;
            try
            {
                $this->input->getOption( $option->short ?? $option->long );
            }
            catch ( ezcConsoleOptionNotExistsException $e )
            {
                $exceptionThrown = true;
            }
            $this->assertTrue( $exceptionThrown, "Exception not unregistered correctly -{$option->short}/--{$option->long}." );
        }

        $this->assertEquals( 0, count( $this->input->getOptions() ) );
    }

    public function testUnregisterOptionFailure()
    {
        $option = new ezcConsoleOption( "x", "execute" );
        try
        {
            $this->input->unregisterOption( $option );
        }
        catch ( ezcConsoleOptionNotExistsException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on unregistering a non existent option." );
    }

    public function testFromStringSuccess()
    {
        $param = new ezcConsoleInput();
        $param->registerOptionString( '[a:|all:][u?|user?][i|info][o+test|overall+][d*|destroy*]' );
        $res['a'] = new ezcConsoleOption(
            'a', 
            'all', 
            ezcConsoleInput::TYPE_NONE, 
            NULL, 
            false, 
            'No help available.', 
            'Sorry, there is no help text available for this parameter.', 
            [], 
            [], 
            true 
        );
        $res['u'] = new ezcConsoleOption(
            'u',
            'user',
            ezcConsoleInput::TYPE_STRING,
            '',
            false,
            'No help available.',
            'Sorry, there is no help text available for this parameter.',
            [],
            [],
            true
        );
        $res['o'] = new ezcConsoleOption(
            'o',
            'overall',
            ezcConsoleInput::TYPE_STRING,
            'test',
            true,
            'No help available.',
            'Sorry, there is no help text available for this parameter.',
            [],
            [],
            true
        );
        $res['d'] = new ezcConsoleOption(
            'd',
            'destroy',
            ezcConsoleInput::TYPE_NONE,
            null,
            true,
            'No help available.',
            'Sorry, there is no help text available for this parameter.',
            [],
            [],
            true
        );
        $this->assertEquals( $res['a'], $param->getOption( 'a' ), 'Option -a not registered correctly.'  );
        $this->assertEquals( $res['u'], $param->getOption( 'u' ), 'Option -u not registered correctly.'  );
        $this->assertEquals( $res['o'], $param->getOption( 'o' ), 'Option -o not registered correctly.'  );
        $this->assertEquals( $res['d'], $param->getOption( 'd' ), 'Option -d not registered correctly.'  );
    }

    public function testFromStringFailure()
    {
        $param = new ezcConsoleInput();
        try
        {
            $param->registerOptionString( '[a:]' );
        }
        catch ( ezcConsoleOptionStringNotWellformedException $e )
        {
            return;
        }
        $this->fail( "Exception not thrown on not wellformed option string." );
    }

    /**
     * testRegisterAliasSuccess
     * 
     * @access public
     */
    public function testRegisterAliasSuccess()
    {
        $validParams = [];
        foreach ( $this->input->getOptions() as $param )
        {
            $validParams[$param->short] = $param;
        }
        foreach ( $this->testAliasesSuccess as $alias )
        {
            $this->input->registerAlias( $alias['short'], $alias['long'], $validParams[$alias['ref']]  );
            $this->assertTrue( $this->input->hasOption( $alias['short'] ), "Short name not available after alias registration." );
            $this->assertTrue( $this->input->hasOption( $alias['long'] ), "Long name not available after alias registration." );
        }
    }
    
    /**
     * testRegisterAliasFailure
     * 
     * @access public
     */
    public function testRegisterAliasFailure()
    {
        $refOption = new ezcConsoleOption( 'foo', 'bar' );
        foreach ( $this->testAliasesFailure as $alias )
        {
            $exceptionThrown = false;
            try 
            {
                $this->input->registerAlias( $alias['short'], $alias['long'], $refOption );
            }
            catch ( ezcConsoleOptionNotExistsException $e )
            {
                $exceptionThrown = true;
            }
            $this->assertTrue( $exceptionThrown, "Exception not thrown on regstering invalid alias --{$alias['short']}/--{$alias['long']}." );
        }
        foreach ( $this->testOptions as $option )
        {
            $exceptionThrown = false;
            try 
            {
                $this->input->registerAlias( $option['short'], $option['long'], $this->input->getOption( "t" ) );
            }
            catch ( ezcConsoleOptionAlreadyRegisteredException $e )
            {
                $exceptionThrown = true;
            }
            $this->assertTrue( $exceptionThrown, "Exception not thrown on regstering already existent option as alias --{$alias['short']}/--{$alias['long']}." );
        }
    }
    
    public function testUnregisterAliasSuccess()
    {
        // test preperation
        $validParams = [];
        foreach ( $this->input->getOptions() as $param )
        {
            $validParams[$param->short] = $param;
        }
        foreach ( $this->testAliasesSuccess as $alias )
        {
            $this->input->registerAlias( $alias['short'], $alias['long'], $validParams[$alias['ref']]  );
        }

        foreach ( $this->testAliasesSuccess as $alias )
        {
            $this->assertTrue( $this->input->hasOption( $alias['short'] ), "Alias incorrectly registered, cannot unregister." );
            $this->input->unregisterAlias( $alias['short'], $alias['long'] );
            $this->assertFalse( $this->input->hasOption( $alias['short'] ), "Alias incorrectly unregistered." );
        }
    }
    
    public function testUnregisterAliasFailure()
    {
        foreach ( $this->testOptions as $option )
        {
            $exceptionThrown = false;
            try
            {
                $this->input->unregisterAlias( !empty( $option['short'] ) ? $option['short'] : "f", $option['long'] );
            }
            catch ( ezcConsoleOptionNoAliasException $e )
            {
                $exceptionThrown = true;
            }
            $this->assertTrue( $exceptionThrown, "Exception not trown on try to unregister an option as an alias." );
        }
    }

    public function testGetAccessSuccess()
    {
        $this->assertNull( $this->input->argumentDefinition );
    }

    public function testGetAccessFailure()
    {
        try
        {
            echo $this->input->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "ezcBasePropertyNotFoundException not thrown on get access to invalid property foo." );
    }

    public function testSetAccessSuccess()
    {
        $this->assertSetProperty(
            $this->input,
            "argumentDefinition",
            [new ezcConsoleArguments(), null]
        );
    }

    public function testSetAccessFailure()
    {
        $this->assertSetPropertyFails(
            $this->input,
            "argumentDefinition",
            ["", "foo", 23, true, [], new stdClass()],
            "ezcBaseValueException"
        );
        $this->assertSetPropertyFails(
            $this->input,
            "foo",
            [""],
            "ezcBasePropertyNotFoundException"
        );
    }

    public function testIssetAccess()
    {
        $this->assertTrue( isset( $this->input->argumentDefinition ) );
        $this->assertFalse( isset( $this->input->foo ) );
    }

    // Single parameter tests
    public function testProcessSuccessSingleShortNoValue()
    {
        $args = ['foo.php', '-t'];
        $res = ['t' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessSingleShortValue()
    {
        $args = ['foo.php', '-o', 'bar'];
        $res = ['o' => 'bar'];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessSingleLongNoValue()
    {
        $args = ['foo.php', '--testing'];
        $res = ['t' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessSingleLongValue()
    {
        $args = ['foo.php', '--original', 'bar'];
        $res = ['o' => 'bar'];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessFailureSingleShortDefault()
    {
        $args = ['foo.php', '-b'];
        $res = ['b' => 42];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionMissingValueException' );
    }
    
    public function testProcessFailureSingleLongDefault()
    {
        $args = ['foo.php', '--build'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionMissingValueException' );
    }
    
    public function testProcessSuccessFromArgv()
    {
        $_SERVER["argv"] = ['foo.php', '--build', '42'];
        $this->input->process();
        $this->assertEquals(
            ["b" => 42, "d" => "world"],
            $this->input->getOptionValues(),
            "Processing from \$_SERVER['argv'] did not work."
        );
    }
    
    public function testProcessSuccessGetOptionValuesLongnames()
    {
        $_SERVER["argv"] = ['foo.php', '--build', '42'];
        $this->input->process();
        $this->assertEquals(
            ["build" => 42, "destroy" => "world"],
            $this->input->getOptionValues( true ),
            "Processing from \$_SERVER['argv'] did not work."
        );
    }

    public function testProcessSuccessSingleShortNoValueArguments()
    {
        $args = ['foo.php', '-s', '--', '-foo', '--bar', 'baz'];
        $res = ['s' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessSingleLongNoValueArguments()
    {
        $args = ['foo.php', '--subway', '--', '-foo', '--bar', 'baz'];
        $res = ['s' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }

    // Multiple parameter tests
    public function testProcessSuccessMultipleShortNoValue()
    {
        $args = ['foo.php', '-t', '-s'];
        $res = ['t' => true, 's' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessMultipleShortValue()
    {
        $args = ['foo.php', '-o', 'bar', '-b', '23'];
        $res = ['o' => 'bar', 'b' => 23];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessMultipleLongNoValue()
    {
        $args = ['foo.php', '--testing', '--subway'];
        $res = ['t' => true, 's' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessMultipleLongValue()
    {
        $args = ['foo.php', '--original', 'bar', '--build', '23'];
        $res = ['o' => 'bar', 'b' => 23];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessMultipleLongValueWithEquals()
    {
        $args = ['foo.php', '--original', 'bar', '--build=23'];
        $res = ['o' => 'bar', 'b' => 23];
        $this->commonProcessTestSuccess( $args, $res );
    }

    public function testProcessFailureMultipleShortDefault()
    {
        $args = ['foo.php', '-b', '-d'];
        $res = ['b' => 42, 'd' => 'world'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionMissingValueException' );
    }

    public function testProcessFailureMultipleLongDefault()
    {
        $args = ['foo.php', '--build', '--destroy'];
        $res = ['b' => 42, 'd' => 'world'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionMissingValueException' );
    }
    
    // Bug #8645: Default values not set correctly in ezcConsoleInput
    public function testProcessSuccessDefault()
    {
        $args = ['foo.php'];
        $res = ['b' => 42, 'd' => 'world'];
        $this->commonProcessTestSuccess( $args, $res );
    }

    public function testProcessSuccessMultipleLongSameNoValue()
    {
        $args = ['foo.php', '--visual', '--visual'];
        $res = ['v' => [true, true]];
        $this->commonProcessTestSuccess( $args, $res );
    }

    public function testProcessSuccessArguments_1()
    {
        $args = ['foo.php', '--original', 'bar', '--build', '23', 'argument', '1', '2'];
        $res = [0 => 'argument', 1 => '1', 2 => '2'];
        $this->argumentsProcessTestSuccess( $args, $res );
    }

    public function testProcessSuccessDependencies()
    {
        $args = ['foo.php', '-t', '-o', 'bar', '--build', 23, '-y', 'text', '--yank', 'moretext', '-c'];
        $res = ['t' => true, 'o' => 'bar', 'b' => 23, 'y' => ['text', 'moretext'], 'c' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessExclusions()
    {
        $args = ['foo.php', '-o', 'bar', '--build', 23, '--edit'];
        $res = ['o' => 'bar', 'b' => 23, 'e' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }

    public function testProcessSuccessDependenciesExclusions()
    {
        $args = ['foo.php', '-t', '-o', 'bar', '-n'];
        $res = ['t' => true, 'o' => 'bar', 'n' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }

    public function testProcessSuccessDependencieValues()
    {
        $rule = new ezcConsoleOptionRule( $this->input->getOption( "y" ), ["foo", "bar"] );
        $option = new ezcConsoleOption( "x", "execute" );
        $option->addDependency( $rule );
        $this->input->registerOption( $option );

        $args = ['foo.php', '-x', '-y', 'bar'];

        $res = ['x' => true, 'y' => ['bar']];

        $this->commonProcessTestSuccess( $args, $res );
    }
    

    public function testProcessSuccessExclusionValues()
    {
        $rule = new ezcConsoleOptionRule( $this->input->getOption( "y" ), ["foo", "bar"] );
        $option = new ezcConsoleOption( "x", "execute" );
        $option->addExclusion( $rule );
        $this->input->registerOption( $option );

        $args = ['foo.php', '-x', '-y', 'baz'];

        $res = ['x' => true, 'y' => ['baz']];

        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessMandatory()
    {
        $args = ['foo.php', '-q'];
        $this->input->registerOption(
            $this->createFakeOption(
                ['short'     => 'q', 'long'      => 'quite', 'options'   => ['mandatory' => true]]
            )
        );
        $res = ['q' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessMandatoryDefault()
    {
        $args = ['foo.php', '-q'];
        $this->input->registerOption(
            $this->createFakeOption(
                ['short'     => 'q', 'long'      => 'quite', 'options'   => ['default'   => 'test', 'mandatory' => true]]
            )
        );
        $res = ['q' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessSuccessHelp()
    {
        $args = ['foo.php', '-h'];
        $this->input->registerOption(
            $this->createFakeOption(
                ['short'     => 'q', 'long'      => 'quite', 'options'   => ['mandatory' => true]]
            )
        );
        $this->input->registerOption(
            $this->createFakeOption(
                ['short'     => 'h', 'long'      => 'help', 'options'   => ['isHelpOption' => true]]
            )
        );
        $res = ['h' => true];
        $this->commonProcessTestSuccess( $args, $res );
    }

    public function testProcessSuccessNewArgumentsSimple()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );

        $this->input->process(
            ["foo.php", "'some file'", "file"]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( "file", $this->input->argumentDefinition["file2"]->value );
    }

    public function testProcessFailureNewArgumentsSimple()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );

        $args = ["foo.php"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessFailureNewArgumentsTooMany()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );

        $args = ["foo.php", "'test'", "'foo'", "'bar'"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleTooManyArgumentsException' );
    }

    public function testProcessSuccessNewArgumentsOptionalAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;

        $this->input->process(
            ["foo.php", "'some file'", "file"]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( "file", $this->input->argumentDefinition["file2"]->value );
    }

    public function testProcessFailureNewArgumentsOptionalAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;

        $args = ["foo.php"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessSuccessNewArgumentsAutoOptionalAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "file3" );

        $this->input->process(
            ["foo.php", "'some file'", "file", "\"another file\""]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( "file", $this->input->argumentDefinition["file2"]->value );
        $this->assertEquals( "another file", $this->input->argumentDefinition["file3"]->value );
    }

    public function testProcessFailureNewArgumentsAutoOptionalAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "file3" );

        $args = ["foo.php"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessSuccessNewArgumentsOptionalNotAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;

        $this->input->process(
            ["foo.php", "'some file'"]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( null, $this->input->argumentDefinition["file2"]->value );
    }

    // Issue #10873: ezcConsoleArgument default value not working
    public function testProcessSuccessNewArgumentsOptionalNotAvailableDefault()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;
        $this->input->argumentDefinition[1]->default   = "some other file";

        $this->input->process(
            ["foo.php", "'some file'"]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( "some other file", $this->input->argumentDefinition["file2"]->value );
    }

    public function testProcessFailureNewArgumentsOptionalNotAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;

        $args = ["foo.php"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessSuccessNewArgumentsAutoOptionalNotAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "file3" );

        $this->input->process(
            ["foo.php", "'some file'"]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( null, $this->input->argumentDefinition["file2"]->value );
        $this->assertEquals( null, $this->input->argumentDefinition["file3"]->value );
    }

    public function testProcessFailureNewArgumentsAutoOptionalNotAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->mandatory = false;
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "file3" );

        $args = ["foo.php"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessSuccessNewArgumentsMultipleOne()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[0]->multiple = true;

        $this->input->process(
            ["foo.php", "'some file'", "file", "\"another file\""]
        );

        $this->assertEquals( ["some file", "file", "another file"], $this->input->argumentDefinition["file1"]->value );
    }

    public function testProcessFailureNewArgumentsMultipleOne()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[0]->multiple = true;

        $args = ["foo.php"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessSuccessNewArgumentsMultipleMultiple()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->multiple = true;

        $this->input->process(
            ["foo.php", "'some file'", "file", "\"another file\""]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( ["file", "another file"], $this->input->argumentDefinition["file2"]->value );
    }

    public function testProcessFailureNewArgumentsMultipleMultiple()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->multiple = true;

        $args = ["foo.php", "'test'"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessSuccessNewArgumentsMultipleOptionalAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->multiple  = true;
        $this->input->argumentDefinition[1]->mandatory = false;

        $this->input->process(
            ["foo.php", "'some file'", "file", "\"another file\""]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( ["file", "another file"], $this->input->argumentDefinition["file2"]->value );
        
        // Old handling
        $this->assertEquals( ["some file", "file", "another file"], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsMultipleOptionalNotAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->multiple  = true;
        $this->input->argumentDefinition[1]->mandatory = false;

        $this->input->process(
            ["foo.php", "'some file'"]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( null, $this->input->argumentDefinition["file2"]->value );
        
        // Old handling
        $this->assertEquals( ["some file"], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsMultipleAutoOptionalAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[0]->mandatory = false;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->multiple  = true;

        $this->input->process(
            ["foo.php", "'some file'", "file", "\"another file\""]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( ["file", "another file"], $this->input->argumentDefinition["file2"]->value );
        
        // Old handling
        $this->assertEquals( ["some file", "file", "another file"], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsMultipleAutoOptionalNotAvailable()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[0]->mandatory = false;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[1]->multiple  = true;

        $this->input->process(
            ["foo.php", "'some file'"]
        );

        $this->assertEquals( "some file", $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( null, $this->input->argumentDefinition["file2"]->value );
        
        // Old handling
        $this->assertEquals( ["some file"], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsMultipleIgnore()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "file1" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "file2" );
        $this->input->argumentDefinition[0]->multiple = true;

        $this->input->process(
            ["foo.php", "'some file'", "file", "\"another file\""]
        );

        $this->assertEquals( ["some file", "file", "another file"], $this->input->argumentDefinition["file1"]->value );
        $this->assertEquals( null, $this->input->argumentDefinition["file2"]->value );
        
        // Old handling
        $this->assertEquals( ["some file", "file", "another file"], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsTypeInt()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;

        $this->input->process(
            ["foo.php", 23]
        );

        $this->assertEquals( 23, $this->input->argumentDefinition["number"]->value );
        
        // Old handling
        $this->assertEquals( [23], $this->input->getArguments() );
    }

    public function testProcessFailureNewArgumentsTypeInt()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;

        $args = ["foo.php", "'test'"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentTypeViolationException' );
    }

    public function testProcessSuccessNewArgumentsMultipleTypeInt()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[0]->multiple = true;

        $this->input->process(
            ["foo.php", 23, 42]
        );

        $this->assertEquals( [23, 42], $this->input->argumentDefinition["number"]->value );
        
        // Old handling
        $this->assertEquals( [23, 42], $this->input->getArguments() );
    }

    public function testProcessFailureNewArgumentsMultipleTypeInt()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[0]->multiple = true;

        $args = ["foo.php", 23, "test"];

        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentTypeViolationException' );
    }

    public function testProcessSuccessNewArgumentsComplex()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $args = ["foo.php", "-o", "'test file'", "-b", "23", "42", "'test string'", "val1", "val2"];

        $res = ['o' => "test file", 'b' => 23];
        $this->commonProcessTestSuccess( $args, $res );

        $this->assertEquals( 42, $this->input->argumentDefinition["number"]->value );
        $this->assertEquals( "test string", $this->input->argumentDefinition["string"]->value );
        $this->assertEquals( ["val1", "val2"], $this->input->argumentDefinition["array"]->value );
        
        // Old handling
        $this->assertEquals( [42, "test string", "val1", "val2"], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsHelpOptionSet()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $this->input->getOption( 't' )->isHelpOption = true;

        $args = ["foo.php", "-t"];

        $res = ['t' => true];
        $this->commonProcessTestSuccess( $args, $res );

        $this->assertNull( $this->input->argumentDefinition["number"]->value );
        $this->assertNull( $this->input->argumentDefinition["string"]->value );
        $this->assertNull( $this->input->argumentDefinition["array"]->value );

        $this->assertTrue( $this->input->helpOptionSet() );
        
        // Old handling
        $this->assertEquals( [], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsDisallowedSuccess()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $this->input->getOption( 't' )->arguments = false;

        $args = ["foo.php", "-t"];

        $res = ['t' => true];
        $this->commonProcessTestSuccess( $args, $res );

        $this->assertNull( $this->input->argumentDefinition["number"]->value );
        $this->assertNull( $this->input->argumentDefinition["string"]->value );
        $this->assertNull( $this->input->argumentDefinition["array"]->value );

        // Old handling
        $this->assertEquals( [], $this->input->getArguments() );
    }

    public function testProcessSuccessNewArgumentsDisallowedFailure()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $this->input->getOption( 't' )->arguments = false;

        $args = ["foo.php", "-t", "--", "23"];

        $res = ['t' => true];
        $this->commonProcessTestFailure( $args, "ezcConsoleOptionArgumentsViolationException" );
    }

    public function testReset()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $args = ["foo.php", "-o", "'test file'", "-b", "23", "42", "'test string'", "val1", "val2"];

        $res = ['o' => "test file", 'b' => 23];
        $this->commonProcessTestSuccess( $args, $res );

        $this->assertEquals( 42, $this->input->argumentDefinition["number"]->value );
        $this->assertEquals( "test string", $this->input->argumentDefinition["string"]->value );
        $this->assertEquals( ["val1", "val2"], $this->input->argumentDefinition["array"]->value );
        
        // Old handling
        $this->assertEquals( [42, "test string", "val1", "val2"], $this->input->getArguments() );

        $this->input->reset();

        $this->assertEquals( [], $this->input->getOptionValues() );
        foreach ( $this->input->argumentDefinition as $argument )
        {
            $this->assertNull( $argument->value );
        }
        $this->assertEquals( [], $this->input->getArguments() );
    }

    public function testProcessTwice()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[1]->mandatory = false;

        $args = ["foo.php", "-o", "'test file'", "-b", "23", "42", "'test string'"];

        $res = ['o' => "test file", 'b' => 23];
        $this->commonProcessTestSuccess( $args, $res );

        $this->assertEquals( 42, $this->input->argumentDefinition["number"]->value );
        $this->assertEquals( "test string", $this->input->argumentDefinition["string"]->value );
        
        // Old handling
        $this->assertEquals( [42, "test string"], $this->input->getArguments() );

        // Second run

        $args = ["foo.php", "-t", '23'];

        $res = ['t' => true];
        $this->commonProcessTestSuccess( $args, $res );

        $this->assertEquals( 23, $this->input->argumentDefinition["number"]->value );
        $this->assertEquals( null, $this->input->argumentDefinition["string"]->value );
        
        // Old handling
        $this->assertEquals( ['23'], $this->input->getArguments() );
    }

    public function testProcessFailureNewArgumentsComplexType()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $args = ["foo.php", "-o", "'test file'", "-b", "23", "foo", "'test string'", "val1", "val2"];

        $res = ['o' => "test file", 'b' => 23];
        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentTypeViolationException' );
    }

    public function testProcessFailureNewArgumentsComplexMissing()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $args = ["foo.php", "-o", "'test file'", "-b", "23", "42"];

        $res = ['o' => "test file", 'b' => 23];
        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessFailureNewArgumentsComplexMissing_2()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "string" );
        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "array" );
        $this->input->argumentDefinition[2]->multiple = true;

        $args = ["foo.php", "-o", "'test file'", "-b", "23", "42", "'test string'"];

        $res = ['o' => "test file", 'b' => 23];
        $this->commonProcessTestFailure( $args, 'ezcConsoleArgumentMandatoryViolationException' );
    }

    public function testProcessFailureNewArgumentsSwitchedOff()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[0]->type = ezcConsoleInput::TYPE_INT;

        $args = ["foo.php", "-v", "--", 23];

        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionArgumentsViolationException' );
    }

    public function testProcessFailureExistance_1()
    {
        $args = ['foo.php', '-q'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionNotExistsException' );
    }
    
    public function testProcessFailureExistance_2()
    {
        $args = ['foo.php', '-tools'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionNotExistsException' );
    }
    
    public function testProcessFailureExistance_3()
    {
        $args = ['foo.php', '-testingaeiou'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionNotExistsException' );
    }
    
    public function testProcessFailureTypeInt()
    {
        $args = ['foo.php', '-b', 'not_an_int'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionTypeViolationException' );
    }
    
    // Bug #9046: New bug: [ConsoleTools] Last argument not treated invalid option value
    public function testProcessNoFailureTypeNone()
    {
        $args = ['foo.php', '-s', 'a_parameter'];
        $res = ["s" => true];
        $this->commonProcessTestSuccess( $args, $res );
    }
    
    public function testProcessFailureTypeNone()
    {
        $args = ['foo.php', '-s', 'a_parameter', 'another_parameter'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionTypeViolationException' );
    }
    
    public function testProcessFailureNovalue()
    {
        $args = ['foo.php', '-o'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionMissingValueException' );
    }
    
    public function testProcessFailureMultiple()
    {
        $args = ['foo.php', '-d', 'mars', '--destroy', 'venus'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionTooManyValuesException' );
    }
    
    public function testProcessFailureDependencies()
    {
        $args = [
            'foo.php',
            '-t',
            //            '-o',
            //            'bar',
            '--build',
            23,
            '-y',
            'text',
            '--yank',
            'moretext',
            '-c',
        ];
        $this->commonProcessTestFailure(
            $args,
            'ezcConsoleOptionDependencyViolationException',
            "The option 'console' depends on the option 'original' but this one was not submitted."
        );
    }

    public function testProcessFailureDependencieValues()
    {
        $rule = new ezcConsoleOptionRule( $this->input->getOption( "y" ), ["foo", "bar"] );
        $option = new ezcConsoleOption( "x", "execute" );
        $option->addDependency( $rule );
        $this->input->registerOption( $option );

        $args = ['foo.php', '-y', 'baz', '-x'];

        $this->commonProcessTestFailure(
            $args,
            'ezcConsoleOptionDependencyViolationException',
            "The option 'execute' depends on the option 'yank' to have a value in 'foo, bar' but this one was not submitted."
        );
    }
    
    public function testProcessFailureExclusions()
    {
        $args = ['foo.php', '-t', '-o', 'bar', '--build', 23, '--edit'];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionExclusionViolationException' );
    }

    public function testProcessFailureExclusionValues()
    {
        $rule = new ezcConsoleOptionRule( $this->input->getOption( "y" ), ["foo", "bar"] );
        $option = new ezcConsoleOption( "x", "execute" );
        $option->addExclusion( $rule );
        $this->input->registerOption( $option );

        $args = ['foo.php', '-y', 'bar', '-x'];

        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionExclusionViolationException' );
    }
    
    public function testProcessFailureArguments()
    {
        $args = [
            'foo.php',
            '-t',
            '--visual',
            // This one forbids arguments
            '-o',
            'bar',
            'someargument',
        ];
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionArgumentsViolationException' );
    }
    
    public function testProcessFailureMandatory()
    {
        $args = ['foo.php', '-s'];
        $this->input->registerOption(
            $this->createFakeOption(
                ['short'     => 'q', 'long'      => 'quite', 'options'   => ['mandatory' => true]]
            )
        );
        $this->commonProcessTestFailure( $args, 'ezcConsoleOptionMandatoryViolationException' );
    }

    public function testGetHelp1()
    {
        $res = [['-t / --testing', 'No help available.'], ['-s / --subway', 'No help available.'], ['--carry', 'No help available.'], ['-v / --visual', 'No help available.'], ['-o / --original', 'No help available.'], ['-b / --build', 'No help available.'], ['-d / --destroy', 'No help available.'], ['-y / --yank', 'Some stupid short text.'], ['-c / --console', 'Some stupid short text.'], ['-e / --edit', 'No help available.'], ['-n / --new', 'No help available.']];
        $this->assertEquals( 
            $res,
            $this->input->getHelp(),
            'Help array was not generated correctly.'
        );
    }

    public function testGetHelpWithGrouping()
    {
        $res = [['Section 1', ''], ['-t / --testing', 'No help available.'], ['--carry', 'No help available.'], ['-b / --build', 'No help available.'], ['', ''], ['Another section', ''], ['-c / --console', 'Some stupid short text.'], ['-n / --new', 'No help available.'], ['-e / --edit', 'No help available.'], ['', ''], ['Third section', ''], ['-s / --subway', 'No help available.'], ['-v / --visual', 'No help available.'], ['-o / --original', 'No help available.'], ['-d / --destroy', 'No help available.'], ['', ''], ['Last section', ''], ['-y / --yank', 'Some stupid short text.']];
        $this->assertEquals( 
            $res,
            $this->input->getHelp(
                false,
                [],
                ['Section 1' => ['t', 'carry', 'build'], 'Another section' => ['c', 'new', 'edit'], 'Third section' => ['subway', 'v', 'o', 'd'], 'Last section' => ['y']]
            ),
            'Help array was not generated correctly.'
        );
    }

    public function testGetHelpNewArgs()
    {
        $res = [['-t / --testing', 'No help available.'], ['-s / --subway', 'No help available.'], ['--carry', 'No help available.'], ['-v / --visual', 'No help available.'], ['-o / --original', 'No help available.'], ['-b / --build', 'No help available.'], ['-d / --destroy', 'No help available.'], ['-y / --yank', 'Some stupid short text.'], ['-c / --console', 'Some stupid short text.'], ['-e / --edit', 'No help available.'], ['-n / --new', 'No help available.'], ["Arguments:", ""], ['<string:text>', 'A text.'], ['<int:number>', 'A number.']];

        $this->input->argumentDefinition = new ezcConsoleArguments();
        
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "text" );
        $this->input->argumentDefinition[0]->shorthelp = 'A text.';
        $this->input->argumentDefinition[0]->longhelp = 'This argument is a simple text.';

        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[1]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1]->shorthelp = 'A number.';
        $this->input->argumentDefinition[1]->longhelp = 'This argument is a number.';

        $this->assertEquals( 
            $res,
            $this->input->getHelp(),
            'Help array was not generated correctly.'
        );
    }
    
    public function testGetHelp2()
    {
        $res = [['-t / --testing', 'Sorry, there is no help text available for this parameter.'], ['-s / --subway', 'Sorry, there is no help text available for this parameter.'], ['--carry', 'Sorry, there is no help text available for this parameter.'], ['-v / --visual', 'Sorry, there is no help text available for this parameter.'], ['-o / --original', 'Sorry, there is no help text available for this parameter.'], ['-b / --build', 'Sorry, there is no help text available for this parameter.'], ['-d / --destroy', 'Sorry, there is no help text available for this parameter.'], ['-y / --yank', 'Some even more stupid, but somewhat longer long describtion.'], ['-c / --console', 'Some even more stupid, but somewhat longer long describtion.'], ['-e / --edit', 'Sorry, there is no help text available for this parameter.'], ['-n / --new', 'Sorry, there is no help text available for this parameter.']];
        $this->assertEquals( 
            $res,
            $this->input->getHelp( true ),
            'Help array was not generated correctly.'
        );
        
    }
    
    public function testGetHelp2NewArgs()
    {
        $res = [['-t / --testing', 'Sorry, there is no help text available for this parameter.'], ['-s / --subway', 'Sorry, there is no help text available for this parameter.'], ['--carry', 'Sorry, there is no help text available for this parameter.'], ['-v / --visual', 'Sorry, there is no help text available for this parameter.'], ['-o / --original', 'Sorry, there is no help text available for this parameter.'], ['-b / --build', 'Sorry, there is no help text available for this parameter.'], ['-d / --destroy', 'Sorry, there is no help text available for this parameter.'], ['-y / --yank', 'Some even more stupid, but somewhat longer long describtion.'], ['-c / --console', 'Some even more stupid, but somewhat longer long describtion.'], ['-e / --edit', 'Sorry, there is no help text available for this parameter.'], ['-n / --new', 'Sorry, there is no help text available for this parameter.'], ["Arguments:", ""], ['<string:text>', 'This argument is a simple text.'], ['<int:number>', 'This argument is a number.']];

        $this->input->argumentDefinition = new ezcConsoleArguments();
        
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "text" );
        $this->input->argumentDefinition[0]->shorthelp = 'A text.';
        $this->input->argumentDefinition[0]->longhelp = 'This argument is a simple text.';

        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[1]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1]->shorthelp = 'A number.';
        $this->input->argumentDefinition[1]->longhelp = 'This argument is a number.';

        $this->assertEquals( 
            $res,
            $this->input->getHelp( true ),
            'Help array was not generated correctly.'
        );
        
    }
    
    public function testGetHelp2NewArgsOptional()
    {
        $res = [['-t / --testing', 'Sorry, there is no help text available for this parameter.'], ['-s / --subway', 'Sorry, there is no help text available for this parameter.'], ['--carry', 'Sorry, there is no help text available for this parameter.'], ['-v / --visual', 'Sorry, there is no help text available for this parameter.'], ['-o / --original', 'Sorry, there is no help text available for this parameter.'], ['-b / --build', 'Sorry, there is no help text available for this parameter.'], ['-d / --destroy', 'Sorry, there is no help text available for this parameter.'], ['-y / --yank', 'Some even more stupid, but somewhat longer long describtion.'], ['-c / --console', 'Some even more stupid, but somewhat longer long describtion.'], ['-e / --edit', 'Sorry, there is no help text available for this parameter.'], ['-n / --new', 'Sorry, there is no help text available for this parameter.'], ["Arguments:", ""], ['<string:text>', 'This argument is a simple text. (optional)'], ['<int:number>', "This argument is a number. (optional, default = '23')"], ['<string:misc>', "Testing multiple values. (optional, default = 'foo' 'bar' 'baz')"]];

        $this->input->argumentDefinition = new ezcConsoleArguments();
        
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "text" );
        $this->input->argumentDefinition[0]->shorthelp = 'A text.';
        $this->input->argumentDefinition[0]->longhelp  = 'This argument is a simple text.';
        $this->input->argumentDefinition[0]->mandatory = false;

        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[1]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1]->shorthelp = 'A number.';
        $this->input->argumentDefinition[1]->longhelp  = 'This argument is a number.';
        $this->input->argumentDefinition[1]->mandatory = false;
        $this->input->argumentDefinition[1]->default   = 23;

        $this->input->argumentDefinition[2] = new ezcConsoleArgument( "misc" );
        $this->input->argumentDefinition[2]->shorthelp = 'A misc argument.';
        $this->input->argumentDefinition[2]->longhelp  = 'Testing multiple values.';
        $this->input->argumentDefinition[2]->multiple  = true;
        $this->input->argumentDefinition[2]->mandatory = false;
        $this->input->argumentDefinition[2]->default   = ["foo", "bar", "baz"];

        $this->assertEquals( 
            $res,
            $this->input->getHelp( true ),
            'Help array was not generated correctly.'
        );
        
    }
    
    public function testGetHelp3()
    {
        $res = [['-t / --testing', 'No help available.'], ['-s / --subway', 'No help available.'], ['-v / --visual', 'No help available.']];
        $this->assertEquals( 
            $res,
            $this->input->getHelp(false, ['t', 's', 'v'] ),
            'Help array was not generated correctly.'
        );
    }
    
    public function testGetHelp4()
    {
        $res = [['-t / --testing', 'Sorry, there is no help text available for this parameter.'], ['-s / --subway', 'Sorry, there is no help text available for this parameter.'], ['-y / --yank', 'Some even more stupid, but somewhat longer long describtion.'], ['-e / --edit', 'Sorry, there is no help text available for this parameter.'], ['-n / --new', 'Sorry, there is no help text available for this parameter.']];
        $this->assertEquals( 
            $res,
            $this->input->getHelp( true, ['t', 'subway', 'yank', 'e', 'n'] ),
            'Help array was not generated correctly.'
        );
        
    }
    
    public function testGetSynopsis()
    {
        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [-t] [-s] [--carry] [-v] [-o <string>] [-b 42] [-d "world"] [-y <string>] [-c] [-e] [-n]  [[--] <args>]',
            $this->input->getSynopsis(),
            'Program synopsis not generated correctly.'
        );
    }

    // Issue #012561 : getSynopsis() bugs when at least 2 options don't have short-names.
    public function testGetSynopsisLongOptionsWithoutShortNames()
    {
        $input = new ezcConsoleInput();
        $input->registerOption(
            new ezcConsoleOption(
                "",
                "set-dericktory",
                ezcConsoleInput::TYPE_NONE
            )
        );

        $input->registerOption(
            new ezcConsoleOption(
                "",
                "set-directoby",
                ezcConsoleInput::TYPE_NONE
            )
        );

        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [--set-dericktory] [--set-directoby]  [[--] <args>]',
            $input->getSynopsis(),
            'Program synopsis not generated correctly.'
        );
    }

    public function testGetSynopsisNewArgumentsSimple()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "text" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[1]->type = ezcConsoleInput::TYPE_INT;

        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [-t] [-s] [--carry] [-v] [-o <string>] [-b 42] [-d "world"] [-y <string>] [-c] [-e] [-n] [--] <string:text> <int:number>',
            $this->input->getSynopsis(),
            'Program synopsis not generated correctly.'
        );
    }
    
    public function testGetSynopsisNewArgumentsMultiple()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "text" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[1]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1]->multiple = true;

        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [-t] [-s] [--carry] [-v] [-o <string>] [-b 42] [-d "world"] [-y <string>] [-c] [-e] [-n] [--] <string:text> <int:number> [<int:number> ...]',
            $this->input->getSynopsis(),
            'Program synopsis not generated correctly.'
        );
    }
    
    public function testGetSynopsisNewArgumentsOptional()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "text" );
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[1]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1]->mandatory = false;

        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [-t] [-s] [--carry] [-v] [-o <string>] [-b 42] [-d "world"] [-y <string>] [-c] [-e] [-n] [--] <string:text> [<int:number>]',
            $this->input->getSynopsis(),
            'Program synopsis not generated correctly.'
        );
    }
    
    public function testGetSynopsisNewArgumentsMultipleOptional()
    {
        $this->input->argumentDefinition = new ezcConsoleArguments();
        $this->input->argumentDefinition[0] = new ezcConsoleArgument( "text" );
        $this->input->argumentDefinition[0]->mandatory = false;
        $this->input->argumentDefinition[1] = new ezcConsoleArgument( "number" );
        $this->input->argumentDefinition[1]->type = ezcConsoleInput::TYPE_INT;
        $this->input->argumentDefinition[1]->multiple = true;

        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [-t] [-s] [--carry] [-v] [-o <string>] [-b 42] [-d "world"] [-y <string>] [-c] [-e] [-n] [--] [<string:text>] [<int:number>] [<int:number> ...]',
            $this->input->getSynopsis(),
            'Program synopsis not generated correctly.'
        );
    }
    
    public function testGetHelpTable()
    {
        $output = new ezcConsoleOutput();
        
        $res = new ezcConsoleTable( $output, 80 ); 
        $res[0][0]->content = '-t / --testing';
        $res[0][1]->content = 'Sorry, there is no help text available for this parameter.';
                
        $res[1][0]->content = '-s / --subway';
        $res[1][1]->content = 'Sorry, there is no help text available for this parameter.';
                
        $res[2][0]->content = '-y / --yank';
        $res[2][1]->content = 'Some even more stupid, but somewhat longer long describtion.';
                
        $res[3][0]->content = '-e / --edit';
        $res[3][1]->content = 'Sorry, there is no help text available for this parameter.';
                
        $table = new ezcConsoleTable( $output, 80 );
        $table = $this->input->getHelpTable( $table, true, ['t', 'subway', 'yank', 'e'] );
        $this->assertEquals(
            $res,
            $table,
            'Help table not generated correctly.'
        );
    }
    
    public function testGetHelpTableGrouping()
    {
        $output = new ezcConsoleOutput();
        
        $res = new ezcConsoleTable( $output, 80 ); 
        $res[0][0]->content = 'Section uno';
        $res[0][1]->content = '';

        $res[1][0]->content = '-t / --testing';
        $res[1][1]->content = 'Sorry, there is no help text available for this parameter.';

        $res[2][0]->content = '';
        $res[2][1]->content = '';

        $res[3][0]->content = 'Section 2';
        $res[3][1]->content = '';

        $res[4][0]->content = '-e / --edit';
        $res[4][1]->content = 'Sorry, there is no help text available for this parameter.';
                
        $res[5][0]->content = '-s / --subway';
        $res[5][1]->content = 'Sorry, there is no help text available for this parameter.';

        $res[6][0]->content = '';
        $res[6][1]->content = '';

        $res[7][0]->content = 'Final section';
        $res[7][1]->content = '';
                
        $res[8][0]->content = '-y / --yank';
        $res[8][1]->content = 'Some even more stupid, but somewhat longer long describtion.';
                
                
        $table = new ezcConsoleTable( $output, 80 );
        $table = $this->input->getHelpTable(
            $table,
            true,
            ['t', 'subway', 'yank', 'e'],
            ['Section uno' => ['t'], 'Section 2' => ['e', 'subway'], 'Final section' => ['y']]
        );
        $this->assertEquals(
            $res,
            $table,
            'Help table not generated correctly.'
        );
    }

    public function testGetHelpTableDefaultParameters()
    {
        $output = new ezcConsoleOutput();
        
        $res = new ezcConsoleTable( $output, 80 ); 
        $res[0][0]->content = '-t / --testing';
        $res[0][1]->content = 'Sorry, there is no help text available for this parameter.';
                
        $res[1][0]->content = '-s / --subway';
        $res[1][1]->content = 'Sorry, there is no help text available for this parameter.';
                
        $res[2][0]->content = '-y / --yank';
        $res[2][1]->content = 'Some even more stupid, but somewhat longer long describtion.';
                
        $res[3][0]->content = '-e / --edit';
        $res[3][1]->content = 'Sorry, there is no help text available for this parameter.';
                
        $table = new ezcConsoleTable( $output, 80 );
        $table = $this->input->getHelpTable( $table );

        $this->assertEquals( 11, sizeof( $table ), "Expected 11 elements in the generated HelpTable" );
    }

    public function testGetHelpTextBinarySafe()
    {
        $input = new ezcConsoleInput();
        $input->registerOption(
            new ezcConsoleOption(
                'ö',
                'öder',
                ezcConsoleInput::TYPE_NONE,
                null,
                false,
                'ööö äää ööö äää',
                'ööö äää ööö äää ööö äää ööö äää ööö äää ööö äää'
            )
        );

        $res = "Usage: $ {$_SERVER['argv'][0]} [-ö]  [[--] <args>]" . PHP_EOL
. 'Test with UTF-8' . PHP_EOL
. 'characters...' . PHP_EOL
. '' . PHP_EOL
. '-ö / --öder  ööö äää' . PHP_EOL
. '             ööö äää' . PHP_EOL
. '             ööö äää' . PHP_EOL
. '             ööö äää' . PHP_EOL
. '             ööö äää' . PHP_EOL
. '             ööö äää' . PHP_EOL;

        $this->assertEquals(
            $res,
            $input->getHelpText( 'Test with UTF-8 characters...', 20, true ),
            'Help text not generated correctly.'
        );
    }

    public function testGetHelpText()
    {
        $res = "Usage: $ {$_SERVER['argv'][0]} [-y <string>] [-e]  [[--] <args>]" . PHP_EOL
. 'Lala' . PHP_EOL
. '' . PHP_EOL
. '-y / --yank  Some' . PHP_EOL
. '             even' . PHP_EOL
. '             more' . PHP_EOL
. '             stupid,' . PHP_EOL
. '             but' . PHP_EOL
. '             somewhat' . PHP_EOL
. '             longer' . PHP_EOL
. '             long' . PHP_EOL
. '             describtion.' . PHP_EOL
. '-e / --edit  Sorry,' . PHP_EOL
. '             there' . PHP_EOL
. '             is no' . PHP_EOL
. '             help' . PHP_EOL
. '             text' . PHP_EOL
. '             available' . PHP_EOL
. '             for' . PHP_EOL
. '             this' . PHP_EOL
. '             parameter.' . PHP_EOL;

        $this->assertEquals(
            $res,
            $this->input->getHelpText( 'Lala', 20, true, ['e', 'y'] ),
            'Help text not generated correctly.'
        );
    }
    
    public function testGetSynopsis1()
    {
        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [-t] [-s] [-o <string>]  [[--] <args>]',
            $this->input->getSynopsis( ['t', 's', 'o'] ),
            'Program synopsis not generated correctly.'
        );
    }
    
    /**
     * Tests bug #7923. 
     * 
     * @return void
     */
    public function testGetSynopsis2()
    {
        $this->assertEquals( 
            '$ '.$_SERVER['argv'][0].' [-t] [-s] [-v]  [[--] <args>]',
            $this->input->getSynopsis( ['t', 's', 'v'] ),
            'Program synopsis not generated correctly.'
        );
    }
    
    public function testGetSynopsis3()
    {
        $this->assertEquals( 
            '$ ' . $_SERVER['argv'][0] . ' [-s] [-b 42]  [[--] <args>]',
            $this->input->getSynopsis( ['b', 's'] ),
            'Program synopsis not generated correctly.'
        );
    }
    
    public function testGetSynopsis4()
    {
        $this->input->registerOption(
            new ezcConsoleOption(
                "x",
                "execute",
                ezcConsoleInput::TYPE_INT
            )
        );
        $this->assertEquals( 
            '$ ' . $_SERVER['argv'][0] . ' [-s] [-x <int>]  [[--] <args>]',
            $this->input->getSynopsis( ['x', 's'] ),
            'Program synopsis not generated correctly.'
        );
    }

    public function testHelpOptionSet()
    {
        $args = ['foo.php', '-h'];
        $this->input->registerOption(
            $this->createFakeOption(
                ['short'     => 'q', 'long'      => 'quite', 'options'   => ['mandatory' => true]]
            )
        );
        $this->input->registerOption(
            $this->createFakeOption(
                ['short'     => 'h', 'long'      => 'help', 'options'   => ['isHelpOption' => true]]
            )
        );
        $res = ['h' => true];

        $this->assertFalse( $this->input->helpOptionSet(), "Help option seems to be set, algthough nothing was processed." );
        $this->commonProcessTestSuccess( $args, $res );
        $this->assertTrue( $this->input->helpOptionSet(), "Help option seems not to be set, algthough it was." );
    }

    public function testDependOptionNoShortName()
    {
        $inputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'input' )
        );
        $outputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'output' )
        );

        $inputOpt->addDependency(
            new ezcConsoleOptionRule( $outputOpt )
        );
        $outputOpt->addDependency(
            new ezcConsoleOptionRule( $inputOpt )
        );
        
        $args = ['somescript', '--input'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated dependency.' );
        } catch ( ezcConsoleOptionDependencyViolationException $e ) {}
        
        $args = ['somescript', '--output'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated dependency.' );
        } catch ( ezcConsoleOptionDependencyViolationException $e ) {}
    }

    // Issue #014803: Problem with ezcConsoleOption when short option name is empty
    public function testExcludeOptionNoShortName()
    {
        $inputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'input' )
        );
        $outputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'output' )
        );

        $inputOpt->addExclusion(
            new ezcConsoleOptionRule( $outputOpt )
        );
        $outputOpt->addExclusion(
            new ezcConsoleOptionRule( $inputOpt )
        );
        
        $args = ['somescript', '--input'];

        // Should not throw an exception
        $this->input->process( $args );
        
        $args = ['somescript', '--output'];

        // Should not throw an exception
        $this->input->process( $args );
    }

    public function testDependOptionValueNotSetNoShortName()
    {
        $inputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'input' )
        );
        $outputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'output' )
        );

        $inputOpt->addDependency(
            new ezcConsoleOptionRule( $outputOpt, ['foo', 'bar'] )
        );
        $outputOpt->addDependency(
            new ezcConsoleOptionRule( $inputOpt, ['foo', 'bar'] )
        );
        
        $args = ['somescript', '--input'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated dependency.' );
        } catch ( ezcConsoleOptionDependencyViolationException $e ) {}
        
        $args = ['somescript', '--output'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated dependency.' );
        } catch ( ezcConsoleOptionDependencyViolationException $e ) {}

    }

    public function testDependOptionValueWrongValueNoShortName()
    {
        $inputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'input', ezcConsoleInput::TYPE_STRING )
        );
        $outputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'output', ezcConsoleInput::TYPE_STRING )
        );

        $inputOpt->addDependency(
            new ezcConsoleOptionRule( $outputOpt, ['foo', 'bar'] )
        );
        $outputOpt->addDependency(
            new ezcConsoleOptionRule( $inputOpt, ['foo', 'bar'] )
        );
        
        $args = ['somescript', '--output=lala', '--input=lala'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated dependency.' );
        } catch ( ezcConsoleOptionDependencyViolationException $e ) {}
        
        $args = ['somescript', '--input=lala', '--output=lala'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated dependency.' );
        } catch ( ezcConsoleOptionDependencyViolationException $e ) {}

    }

    public function testExcludeOptionValueWrongValueNoShortName()
    {
        $inputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'input', ezcConsoleInput::TYPE_STRING )
        );
        $outputOpt = $this->input->registerOption(
            new ezcConsoleOption( '', 'output', ezcConsoleInput::TYPE_STRING )
        );

        $inputOpt->addExclusion(
            new ezcConsoleOptionRule( $outputOpt, ['foo', 'bar'] )
        );
        $outputOpt->addExclusion(
            new ezcConsoleOptionRule( $inputOpt, ['foo', 'bar'] )
        );
        
        $args = ['somescript', '--output=foo', '--input=lala'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated exclusion.' );
        }
        catch ( ezcConsoleOptionExclusionViolationException $e )
        {
            $this->assertEquals(
                "The option 'input' excludes the option 'output' to have a value in 'foo, bar' but this one was submitted.",
                $e->getMessage()
            );
        }
        
        $args = ['somescript', '--output=lala', '--input=bar'];

        try
        {
            $this->input->process( $args );
            $this->fail( 'Processing did not throw an exception on violated dependency.' );
        }
        catch ( ezcConsoleOptionExclusionViolationException $e )
        {
            $this->assertEquals(
                "The option 'output' excludes the option 'input' to have a value in 'foo, bar' but this one was submitted.",
                $e->getMessage()
            );
        }

    }

    public function testDependencyOptionNotSet()
    {
        $aOpt = $this->input->registerOption(
            new ezcConsoleOption( 'a', 'abbrev', ezcConsoleInput::TYPE_NONE )
        );
        $aOpt->addDependency(
            new ezcConsoleOptionRule( $this->input->getOption( 't' ), [], false )
        );

        $this->commonProcessTestFailure(
            ['foo.php'],
            'ezcConsoleOptionDependencyViolationException'
        );
    }

    public function testExclusionOptionNotSet()
    {
        $aOpt = $this->input->registerOption(
            new ezcConsoleOption( 'a', 'abbrev', ezcConsoleInput::TYPE_NONE )
        );
        $aOpt->addExclusion(
            new ezcConsoleOptionRule( $this->input->getOption( 't' ), [], false )
        );

        $this->commonProcessTestFailure(
            ['foo.php', '-t'],
            'ezcConsoleOptionExclusionViolationException'
        );
    }
    
    private function commonProcessTestSuccess( $args, $res )
    {
        $this->input->process( $args );
        $values = $this->input->getOptionValues();
        $this->assertTrue( count( array_diff( $res, $values ) ) == 0, 'Parameters processed incorrectly.' );
    }
    
    private function commonProcessTestFailure( $args, $exceptionClass, $message = null )
    {
        try 
        {
            $this->input->process( $args );
        }
        catch ( ezcConsoleException $e )
        {
            $this->assertSame(
                $exceptionClass,
                get_class( $e ),
                'Wrong exception thrown for invalid parameter submission. Expected class <'.$exceptionClass.'>, received <'.get_class( $e ).'>'
            );

            if ( $message !== null )
            {
                $this->assertEquals(
                    $message,
                    $e->getMessage(),
                    'Exception message incorrect.'
                );
            }
            return;
        }
        $this->fail( 'Exception not thrown for invalid parameter submition.' );
    }

    private function argumentsProcessTestSuccess( $args, $res )
    {
        $this->input->process( $args );
        $this->assertEquals(
            $res,
            $this->input->getArguments(),
            'Arguments not parsed correctly.'
        );
    }
}
?>
