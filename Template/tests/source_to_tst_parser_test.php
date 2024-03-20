<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.2
 * @filesource
 * @package Template
 * @subpackage Tests
 */

require_once "invariant_parse_cursor.php";

/**
 * @package Template
 * @subpackage Tests
 */
class ezcTemplateSourceToTstParserTest extends ezcTestCase
{
    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( self::class );
    }

    /**
     * Returns a constraint which checks if the input path exist on the filesystem.
     *
     * @return ezcMockFileExistsConstraint
     */
    static public function existsOnDisk()
    {
        return new ezcMockFileExistsConstraint();
    }

    protected function setUp()
    {
        // // required because of Reflection autoload bug
        class_exists( 'ezcTemplateSourceCode' );
        // class_exists( 'ezcTemplateManager' );
        $this->manager = new ezcTemplateManager();
        PHPUnit_Extensions_MockObject_Mock::generate( 'ezcTemplateParser', ["reportElementCursor"], 'MockElement_ezcTemplateParser' );

        $this->basePath = realpath( __DIR__ ) . '/';
        $this->templatePath = $this->basePath . 'templates/';
        $this->templateCompiledPath = $this->basePath . 'compiled/';
        $this->templateStorePath = $this->basePath . 'stored_templates/';
    }

    /**
     * Test parsing template code which does not contain any blocks.
     */
    public function testParsingTextElements()
    {
        $text = "abc def \nshow widget";
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        $items = [[0, 1, 0, 20, 2, 11, 'TextBlock']];

        $this->setupExpectedPositions( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing template code which has a block with a missing end bracket.
     */
    public function testParsingBlockWithMissingBracketReportsError()
    {
        $text = "abc def \n{show widget";
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        $items = [[0, 1, 0, 9, 2, 0, 'TextBlock']];

        $this->setupExpectedPositions( $parser, $text, $source, $items );

        try
        {
            $program = $parser->parseIntoNodeTree();
            self::fail( "No parse exception thrown" );
        }
        catch ( ezcTemplateSourceToTstParserException $e )
        {
        }

        $parser->verify();
    }

    /**
     * Test parsing template code contain all comment types.
     */
    public function testParsingAllCommentTypes()
    {
        self::assertThat( $this->templatePath . "comments_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "comments_test.tpl" );
        // $text = "abc def \n{show /*inside comment*/widget\n$w // eol comment\n}";
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        if ( $parser->debug )
            echo "\ncomments_test.tpl\n";

        $items = [['TextBlock'], ['DocComment', 'commentText', ' Documentation block '], ['TextBlock'], ['Literal'], ['BlockComment', 'commentText', 'inside comment'], ['PlusOperator'], ['EolComment', 'commentText', 'eol comment'], ['Literal'], ['OutputBlock'], ['TextBlock'], ['Literal'], ['EolComment', 'commentText', 'eol comment #2'], ['OutputBlock']];

        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing template code containing all builtin types except arrays.
     */
    public function testParseLiteralTypesExpression()
    {
        self::assertThat( $this->templatePath . "expression_types_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "expression_types_test.tpl" );
        // echo "\nexpression_types_test.tpl\n";
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        if ( $parser->debug )
            echo "\nexpression_types_test.tpl\n";

        $items = [['Literal', 'value', 1], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 42], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', -1234], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1.0], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', -4.2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 0.5], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', "1"], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', "a short string"], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', "a short \"quoted\" string"], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', "1"], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', "a short string"], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', "a short \'quoted\' string"], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', true], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', false], ['OutputBlock']];

        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing template code containing array types.
     * @todo This currently fails, remove return statement when it is fixed.
     */
    public function testParsingArrayExpression()
    {
        self::assertThat( $this->templatePath . "expression_array_types_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "expression_array_types_test.tpl" );
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        if ( $parser->debug )
            echo "\nexpression_array_types_test.tpl\n";

        $items = [['Literal', 'value', []], ['OutputBlock']];

        $items_array1 = [['TextBlock'], ['Literal', 'value', 1], ['Literal', 'value', 2], ['Literal', 'value', 3], ['Literal', 'value', [1, 2, 3]], ['OutputBlock']];

        $items = array_merge( $items,
                              $items_array1, $items_array1, $items_array1 );

        $items_array2 = [['TextBlock'], ['Literal', 'value', 0], ['Literal', 'value', 1], ['Literal', 'value', 1], ['Literal', 'value', 2], ['Literal', 'value', 2], ['Literal', 'value', 3], ['Literal', 'value', [0 => 1, 1 => 2, 2 => 3]], ['OutputBlock']];

        $items = array_merge( $items,
                              $items_array2, $items_array2, $items_array2 );

        $items_array3 = [['TextBlock'], ['Literal', 'value', "abc"], ['Literal', 'value', "def"], ['Literal', 'value', "foo"], ['Literal', 'value', "bar"], ['Literal', 'value', 5], ['Literal', 'value', "el1"], ['Literal', 'value', "key1"], ['Literal', 'value', -50], ['Literal', 'value', ["abc" => "def", "foo" => "bar", 5 => "el1", "key1" => -50]], ['OutputBlock']];

        $items = array_merge( $items,
                              $items_array3, $items_array3, $items_array3 );

        $items_array4 = [['TextBlock'], ['Literal', 'value', 3], ['Literal', 'value', [3]], ['Literal', 'value', 2], ['Literal', 'value', [[3], 2]], ['Literal', 'value', 1], ['Literal', 'value', [[[3], 2], 1]], ['OutputBlock']];

        $items = array_merge( $items,
                              $items_array4 );

        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing template code containing variable statements.
     */
    public function testParsingVariablesExpression()
    {
        self::assertThat( $this->templatePath . "expression_variables_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "expression_variables_test.tpl" );
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        if ( $parser->debug )
            echo "\nexpression_variables_test.tpl\n";

        $items = [['Literal', 'value', 'var'], ['Variable', 'name', 'var'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 'varName'], ['Variable', 'name', 'varName'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 'var_name'], ['Variable', 'name', 'var_name'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 'var_0_name'], ['Variable', 'name', 'var_0_name'], ['OutputBlock']];

        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing template code containing operators expressions.
     */
    public function testParsingOperatorExpression()
    {
        self::assertThat( $this->templatePath . "expression_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "expression_test.tpl" );
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        if ( $parser->debug )
            echo "\nexpression_test.tpl\n";

        $items = [
            ['Literal', 'value', 'item'],
            ['Variable', 'name', 'item'],
            ['PropertyFetchOperator'],
            ['Literal', 'value', 'prop1'],
            ['OutputBlock'],
            ['TextBlock'],
            ['Literal', 'value', 'item'],
            ['Variable', 'name', 'item'],
            ['PropertyFetchOperator'],
            ['Literal', 'value', 0],
            ['OutputBlock'],
            // index 10
            ['TextBlock'],
            ['Literal', 'value', 'item'],
            ['Variable', 'name', 'item'],
            ['Literal', 'value', 'prop1'],
            ['ArrayFetchOperator'],
            ['OutputBlock'],
            ['TextBlock'],
            ['Literal', 'value', 'item'],
            ['Variable', 'name', 'item'],
            ['Literal', 'value', 0],
            // index 20
            ['ArrayFetchOperator'],
            ['OutputBlock'],
            ['TextBlock'],
            ['Literal', 'value', 'item'],
            ['Variable', 'name', 'item'],
            ['Literal', 'value', 42],
            ['ArrayFetchOperator'],
            ['PropertyFetchOperator'],
            ['Literal', 'value', 'subitem'],
            ['Literal', 'value', "test"],
            // index 30
            ['ArrayFetchOperator'],
            ['OutputBlock'],
            ['TextBlock'],
            ['Literal', 'value', 1],
            ['PlusOperator'],
            ['Literal', 'value', 2],
            ['OutputBlock'],
            ['TextBlock'],
            ['Literal', 'value', 1],
            ['MultiplicationOperator'],
            // index 40
            ['Literal', 'value', 2],
            ['PlusOperator'],
            ['Literal', 'value', 3],
            ['OutputBlock'],
            ['TextBlock'],
            ['Literal', 'value', 1],
            ['PlusOperator'],
            ['Literal', 'value', 2],
            ['MinusOperator'],
            ['Literal', 'value', 4],
            // index 50
            ['MultiplicationOperator'],
            ['Literal', 'value', 6],
            ['DivisionOperator'],
            ['Literal', 'value', 'var'],
            ['Variable', 'name', 'var'],
            ['PropertyFetchOperator'],
            ['Literal', 'value', 'lines'],
            ['Literal', 'value', 0],
            ['ArrayFetchOperator'],
            ['MinusOperator'],
            // index 60
            ['Literal', 'value', 5],
            ['PlusOperator'],
            ['Literal', 'value', 100],
            ['ConcatOperator'],
            ['Literal', 'value', "a"],
            ['OutputBlock'],
            ['TextBlock'],
        ];

        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing operators with sub-expressions.
     */
    public function testParsingOperatorSubExpressions()
    {
        self::assertThat( $this->templatePath . "sub_expressions_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "sub_expressions_test.tpl" );
        // echo "\nsub_expressions_test.tpl\n";
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        $items = [['Literal', 'value', 1], ['PlusOperator'], ['Literal', 'value', 2], ['MultiplicationOperator'], ['Literal', 'value', 3], ['Parenthesis'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['MultiplicationOperator'], ['Literal', 'value', 2], ['PlusOperator'], ['Literal', 'value', 3], ['MinusOperator'], ['Literal', 'value', 4], ['DivisionOperator'], ['Literal', 'value', 200], ['MultiplicationOperator'], ['Literal', 'value', 2], ['Parenthesis'], ['Parenthesis'], ['Parenthesis'], ['PlusOperator'], ['Literal', 'value', 5], ['PlusOperator'], ['Literal', 'value', 'node'], ['Variable', 'name', 'node'], ['PlusOperator'], ['Literal', 'value', 200], ['Parenthesis'], ['OutputBlock']];

        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing all supported operators.
     */
    public function testParseOperators()
    {
        self::assertThat( $this->templatePath . "operators_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "operators_test.tpl" );
        $source = new ezcTemplateSourceCode( 'mock', 'mock', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        $items = [['Literal', 'value', 'obj'], ['Variable', 'name', 'obj'], ['PropertyFetchOperator'], ['Literal', 'value', 'prop'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 'a'], ['Variable', 'name', 'a'], ['Literal', 'value', 0], ['ArrayFetchOperator'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['PlusOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['MinusOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 'a'], ['ConcatOperator'], ['Literal', 'value', 'b'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['MultiplicationOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['DivisionOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['ModuloOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['EqualOperator'], ['Literal', 'value', 2], ['EqualOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['NotEqualOperator'], ['Literal', 'value', 2], ['NotEqualOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['IdenticalOperator'], ['Literal', 'value', 2], ['IdenticalOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['NotIdenticalOperator'], ['Literal', 'value', 2], ['NotIdenticalOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['LessThanOperator'], ['Literal', 'value', 2], ['LessThanOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['GreaterThanOperator'], ['Literal', 'value', 2], ['GreaterThanOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['LessEqualOperator'], ['Literal', 'value', 2], ['LessEqualOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['GreaterEqualOperator'], ['Literal', 'value', 2], ['GreaterEqualOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['LogicalAndOperator'], ['Literal', 'value', 2], ['LogicalAndOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['LogicalOrOperator'], ['Literal', 'value', 2], ['LogicalOrOperator'], ['Literal', 'value', 3], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['AssignmentOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['PlusAssignmentOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['MinusAssignmentOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['MultiplicationAssignmentOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['DivisionAssignmentOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['ConcatAssignmentOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['ModuloAssignmentOperator'], ['Literal', 'value', 2], ['OutputBlock'], ['TextBlock'], ['PreIncrementOperator'], ['Literal', 'value', 1], ['OutputBlock'], ['TextBlock'], ['PreDecrementOperator'], ['Literal', 'value', 1], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['PostIncrementOperator'], ['PlusOperator'], ['Literal', 'value', 1], ['PostDecrementOperator'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['PostIncrementOperator'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 1], ['PostDecrementOperator'], ['OutputBlock'], ['TextBlock'], ['NegateOperator'], ['Literal', 'value', 'a'], ['Variable', 'name', 'a'], ['OutputBlock'], ['TextBlock'], ['LogicalNegateOperator'], ['Literal', 'value', 'a'], ['Variable', 'name', 'a'], ['OutputBlock'], ['TextBlock'], ['Literal', 'value', 'a'], ['Variable', 'name', 'a'], ['InstanceOfOperator'], ['Literal', 'value', 'b'], ['Variable', 'name', 'b'], ['OutputBlock']];

        if ( $parser->debug )
            echo "\noperators_test.tpl\n";

//        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing the literal block and escaped text portions.
     */
    public function testParsingLiteralBlock()
    {
        self::assertThat( $this->templatePath . "literal_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "literal_test.tpl" );
        $source = new ezcTemplateSourceCode( 'literal_test.tpl', 'mock:literal_test.tpl', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        if ( $parser->debug )
            echo "\nliteral_test.tpl\n";

        $items = [['TextBlock', 'originalText', "some plain text\n\n"], ['LiteralBlock', 'originalText', "{literal}\n\nno { code } inside here\nand \\{ escaped \\} braces are kept\n{/literal}"], ['TextBlock', 'originalText', "\n\nand \\no/ \{ code \} here either\n"]];

        $this->setupExpectedElements( $parser, $text, $source, $items );

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing the foreach block.
     */
    public function testParsingForeachBlock()
    {
        self::assertThat( $this->templatePath . "foreach_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "foreach_test.tpl" );
        $source = new ezcTemplateSourceCode( 'foreach_test.tpl', 'mock:foreach_test.tpl', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );
//        $parser->debug = true;

        if ( $parser->debug )
            echo "\nforeach_test.tpl\n";

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo "\n\n", ezcTemplateTstTreeOutput::output( $program ), "\n";
        $parser->verify();
    }

    /**
     * Test parsing incorrect "foreach" blocks.
     */
    public function testParsingForeachBlock2()
    {
        $texts = ['{foreach 1 as $i}{/foreach}', '{foreach $objects error $item}{/foreach}'];

        $nFailures = 0;
        foreach ( $texts as $i => $text )
        {
            $source = new ezcTemplateSourceCode( "while_test$i.tpl", "mock:while_test$i.tpl", $text );
            $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

            try
            {
                $program = $parser->parseIntoNodeTree();
                if ( $parser->debug )
                    echo "\n\n", ezcTemplateTstTreeOutput::output( $program ), "\n";
            }
            catch ( Exception $e )
            {
                $nFailures++;
            }

            $parser->verify();

            unset( $parser );
            unset( $source );
        }

        $nOk = count( $texts ) - $nFailures;
        if ( $nOk > 0 )
            $this->fail( "Parser did not fail on $nOk incorrect templates foreach_test2" );
    }


    /**
     * Test parsing the "while" block.
     */
    public function testParsingWhileBlock()
    {
        self::assertThat( $this->templatePath . "while_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "while_test.tpl" );
        $source = new ezcTemplateSourceCode( 'while_test.tpl', 'mock:while_test.tpl', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );
        // $parser->debug = true;

        if ( $parser->debug )
            echo "\nwhile_test.tpl\n";

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo "\n\n", ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();
    }

    /**
     * Test parsing incorrect do/while blocks.
     */
    public function testParsingWhileBlock2()
    {
        $texts = ['{while}{/while}', '{while}'];

        $nFailures = 0;
        foreach ( $texts as $i => $text )
        {
            $ok = true;
            $source = new ezcTemplateSourceCode( "while_test$i.tpl", "mock:while_test$i.tpl", $text );
            $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

            try
            {
                $program = $parser->parseIntoNodeTree();
                if ( $parser->debug )
                    echo "\n\n", ezcTemplateTstTreeOutput::output( $program ), "\n";
            }
            catch ( Exception $e )
            {
                $nFailures++;
            }

            $parser->verify();

            unset( $parser );
            unset( $source );
        }

        $nOk = count( $texts ) - $nFailures;
        if ( $nOk > 0 )
            $this->fail( "Parser did not fail on $nOk incorrect templates while_test2" );
    }

    /**
     * Test parsing the "if" block.
     */
    public function testParsingIfBlock()
    {
        self::assertThat( $this->templatePath . "if_test.tpl", self::existsOnDisk() );

        $text = file_get_contents( $this->templatePath . "if_test.tpl" );
        $source = new ezcTemplateSourceCode( 'if_test.tpl', 'mock:if_test.tpl', $text );
        $parser = new MockElement_ezcTemplateParser( $source, $this->manager );

        // $parser->debug = true;

        if ( $parser->debug )
            echo "\nforeach_test.tpl\n";

        $program = $parser->parseIntoNodeTree();

        if ( $parser->debug )
            echo "\n\n", ezcTemplateTstTreeOutput::output( $program ), "\n";

        $parser->verify();

    }

    /**
     * Sets up expectations for reportElementCursor based on item list $items
     * which contains the start and ending position + expected class element.
     */
    public function setupExpectedElements( $parser, $text, $source, $items )
    {
        $index = 0;
        foreach ( $items as $item )
        {
            $class = 'ezcTemplate' . $item[0];
            if ( substr( $class, -15 ) != 'OperatorTstNode' )
                $class .= 'TstNode';
            if ( isset( $item[1] ) && isset( $item[2] ) )
            {
                $parser->expects( self::at( $index ) )
                    ->method( "reportElementCursor" )
                    ->with( self::anything(),
                            self::anything(),
                            self::logicalAnd( self::isInstanceOf( $class ),
                                              self::hasProperty( $item[1] )->that( self::identicalTo( $item[2] ) ) ) );
            }
            else
            {
                $parser->expects( self::at( $index ) )
                    ->method( "reportElementCursor" )
                    ->with( self::anything(),
                            self::anything(),
                            self::logicalAnd( self::isInstanceOf( $class ) ) );
            }
            ++$index;
        }
        $parser->expects( self::exactly( $index ) )
            ->method( "reportElementCursor" )
            ->withAnyParameters();
    }

    /**
     * Sets up expectations for reportElementCursor based on item list $items
     * which contains the start and ending position + expected class element.
     */
    public function setupExpectedPositions( $parser, $text, $source, $items )
    {
        $index = 0;
        foreach ( $items as $item )
        {
            $startCursor = new ezcTemplateCursor( $text, $item[0], $item[1], $item[2] );
            $endCursor = new ezcTemplateCursor( $text, $item[3], $item[4], $item[5] );
            $class = 'ezcTemplate' . $item[6];
            if ( substr( $class, -15 ) != 'OperatorTstNode' )
                $class .= 'TstNode';
            $element = new $class( $source, $startCursor, $endCursor );
            if ( isset( $item[7] ) && isset( $item[8] ) )
            {
                $property = $item[7];
                $element->$property = $item[8];
            }
            $parser->expects( self::at( $index ) )
                ->method( "reportElementCursor" )
                ->with( $startCursor, $endCursor, $element );
            $startCursor = $endCursor;
            ++$index;
        }
        $parser->expects( self::exactly( $index ) )
            ->method( "reportElementCursor" )
            ->withAnyParameters();
    }
}

?>
