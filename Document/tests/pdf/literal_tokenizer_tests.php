<?php
/**
 * ezcDocumentPdfHyphenatorTests
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
class ezcDocumentPdfLiteralTokenizerTests extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testDefaultTokenizerNoSplit()
    {
        $hyphenator = new ezcDocumentPdfLiteralTokenizer();
        $this->assertSame(
            ['foo', ezcDocumentPdfTokenizer::FORCED],
            $hyphenator->tokenize( 'foo' )
        );
    }

    public function testDefaultTokenizerSingleMiddleSplit()
    {
        $hyphenator = new ezcDocumentPdfLiteralTokenizer();
        $this->assertSame(
            ['foo', ' ', 'bar', ezcDocumentPdfTokenizer::FORCED],
            $hyphenator->tokenize( 'foo bar' )
        );
    }

    public function testDefaultTokenizerConvertTab1()
    {
        $hyphenator = new ezcDocumentPdfLiteralTokenizer();
        $this->assertSame(
            ['        ', ezcDocumentPdfTokenizer::FORCED],
            $hyphenator->tokenize( "\t" )
        );
    }

    public function testDefaultTokenizerConvertTab2()
    {
        $hyphenator = new ezcDocumentPdfLiteralTokenizer();
        $this->assertSame(
            ['        ', ezcDocumentPdfTokenizer::FORCED],
            $hyphenator->tokenize( "   \t" )
        );
    }

    public function testDefaultTokenizerConvertTab3()
    {
        $hyphenator = new ezcDocumentPdfLiteralTokenizer();
        $this->assertSame(
            ['                ', ezcDocumentPdfTokenizer::FORCED],
            $hyphenator->tokenize( "          \t" )
        );
    }

    public function testDefaultTokenizerConvertTab4()
    {
        $hyphenator = new ezcDocumentPdfLiteralTokenizer();
        $this->assertSame(
            ['foo', '     ', 'bar', ezcDocumentPdfTokenizer::FORCED],
            $hyphenator->tokenize( "foo\tbar" )
        );
    }

    public function testDefaultTokenizerConvertTab5()
    {
        $hyphenator = new ezcDocumentPdfLiteralTokenizer();
        $this->assertSame(
            ['foo', '     ', 'bar', ezcDocumentPdfTokenizer::FORCED, 'foo', '     ', 'bar', ezcDocumentPdfTokenizer::FORCED],
            $hyphenator->tokenize( "foo\tbar\nfoo\tbar" )
        );
    }
}
?>
