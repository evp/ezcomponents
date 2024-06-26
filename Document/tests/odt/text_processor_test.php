<?php
/**
 * ezcDocumentOdtTextProcessorTest.
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentOdtTextProcessorTest extends ezcTestCase
{
    protected $sourceRoot;

    protected $targetRoot;

    protected $proc;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function setUp()
    {
        $sourceDoc = new DOMDocument();
        $sourceDoc->registerNodeClass(
            'DOMElement',
            'ezcDocumentLocateableDomElement'
        );
        $this->sourceRoot = $sourceDoc->appendChild(
            $sourceDoc->createElement(
                'docbook'
            )
        );

        $targetDoc = new DOMDocument();
        $this->targetRoot = $targetDoc->appendChild(
            $targetDoc->createElementNS(
                ezcDocumentOdt::NS_ODT_TEXT,
                'text'
            )
        );

        $this->proc = new ezcDocumentOdtTextProcessor();
    }

    public function testNoLiteral()
    {
        $in = $this->sourceRoot->appendChild(
            new DOMText( "Some text   with multiple\t\t\twhitespaces in\n\n \t  it." )
        );
        
        $res = $this->proc->processText( $in, $this->targetRoot );

        $this->assertTrue(
            is_array( $res )
        );
        $this->assertEquals(
            1,
            count( $res )
        );
        $this->assertTrue(
            ( $res = $res[0] ) instanceof DOMNode
        );

        $this->assertEquals(
            XML_TEXT_NODE,
            $res->nodeType
        );

        $this->assertEquals(
            $in->wholeText,
            $res->wholeText
        );
    }

    public function testLiteralNoReplacement()
    {
        $ll = $this->sourceRoot->appendChild(
            $this->sourceRoot->ownerDocument->createElement(
                'literallayout'
            )
        );
        $in = $ll->appendChild(
            new DOMText( "Some text without multiple whitespaces in it." )
        );
        
        $res = $this->proc->processText( $in, $this->targetRoot );

        $this->assertTrue(
            is_array( $res )
        );
        $this->assertEquals(
            1,
            count( $res )
        );
        $this->assertTrue(
            ( $res[0] instanceof DOMNode )
        );

        $this->assertEquals(
            XML_TEXT_NODE,
            $res[0]->nodeType
        );

        $this->assertEquals(
            $in->wholeText,
            $res[0]->wholeText
        );
    }

    public function testLiteralReplacement()
    {
        $ll = $this->sourceRoot->appendChild(
            $this->sourceRoot->ownerDocument->createElement(
                'literallayout'
            )
        );
        $in = $ll->appendChild(
            new DOMText( "Some text   with multiple\t\t\twhitespaces in\n\n \t  it." )
        );

        $res = $this->proc->processText( $in, $this->targetRoot );

        $this->assertTrue(
            is_array( $res )
        );

        // 0  => "Some text "
        // 1  => <text:s c="2"/>
        // 2  => "with multiple"
        // 3  => <text:tab/>
        // 4  => <text:tab/>
        // 5  => <text:tab/>
        // 6  => "whitespaces in"
        // 7  => <text:line-break/>
        // 8  => <text:line-break/>
        // 9  => " "
        // 10  => <text:tab/>
        // 11  => " "
        // 12  => <text:s c="1"/>
        // 13 => "it."

        $this->assertEquals(
            14,
            count( $res )
        );

        $this->assertTextNode(  $res[0],  "Some text " );
        $this->assertSpaceNode( $res[1],  2 );
        $this->assertTextNode(  $res[2],  "with multiple" );
        $this->assertTabNode(   $res[3] );
        $this->assertTabNode(   $res[4] );
        $this->assertTabNode(   $res[5] );
        $this->assertTextNode(  $res[6],  "whitespaces in" );
        $this->assertBreakNode( $res[7] );
        $this->assertBreakNode( $res[8] );
        $this->assertTextNode(  $res[9],  " " );
        $this->assertTabNode(   $res[10] );
        $this->assertTextNode(  $res[11], " " );
        $this->assertSpaceNode( $res[12], 1 );
        $this->assertTextNode(  $res[13], "it." );
    }

    protected function dumpDom( DOMNode $node, $indent = '' )
    {
        echo $indent;
        switch ( $node->nodeType )
        {
            case XML_ELEMENT_NODE:
                echo "-- <{$node->tagName}>";
                break;
            case XML_TEXT_NODE:
                echo '-- "' . $node->nodeValue . '"';
                break;
        }
        echo "\n";

        if ( $node->childNodes !== null )
        {
            foreach ( $node->childNodes as $child )
            {
                $this->dumpDom( $child );
            }
        }
    }

    protected function assertTextNode( DOMNode $node, $text )
    {
        $this->assertEquals(
            XML_TEXT_NODE,
            $node->nodeType
        );
        $this->assertEquals(
            $text,
            $node->wholeText
        );
    }

    protected function assertSpaceNode( $node, $count )
    {
        $this->assertEquals(
            XML_ELEMENT_NODE,
            $node->nodeType
        );
        $this->assertEquals(
            's',
            $node->localName
        );
        $this->assertEquals(
            (string) $count,
            $node->getAttributeNS(
                ezcDocumentOdt::NS_ODT_TEXT,
                'c'
            )
        );
    }

    protected function assertTabNode( $node )
    {
        $this->assertEquals(
            XML_ELEMENT_NODE,
            $node->nodeType
        );
        $this->assertEquals(
            'tab',
            $node->localName
        );
        // Not allowed, must be repeated!
        $this->assertFalse(
            $node->hasAttributeNS(
                ezcDocumentOdt::NS_ODT_TEXT,
                'c'
            )
        );
    }

    protected function assertBreakNode( $node )
    {
        $this->assertEquals(
            XML_ELEMENT_NODE,
            $node->nodeType
        );
        $this->assertEquals(
            'line-break',
            $node->localName
        );
        // Not allowed, must be repeated!
        $this->assertFalse(
            $node->hasAttributeNS(
                ezcDocumentOdt::NS_ODT_TEXT,
                'c'
            )
        );
    }
}



?>
