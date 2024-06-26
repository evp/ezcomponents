<?php
/**
 * ezcDocTestConvertDocbookDocbook
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
class ezcDocumentXmlBaseTests extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testLoadXmlDocumentFromFile()
    {
        $doc = new ezcDocumentDocbook();
        $doc->loadFile( 
            __DIR__ . '/files/xhtml_sample_basic.xml'
        );

        $this->assertTrue(
            $doc->getDomDocument() instanceof DOMDocument,
            'DOMDocument not created properly'
        );
    }

    public function testLoadXmlDocumentFromString()
    {
        $string = file_get_contents(
            __DIR__ . '/files/xhtml_sample_basic.xml'
        );

        $doc = new ezcDocumentDocbook();
        $doc->loadString( $string );

        $this->assertTrue(
            $doc->getDomDocument() instanceof DOMDocument,
            'DOMDocument not created properly'
        );
    }

    public function testLoadErroneousXmlDocument()
    {
        $doc = new ezcDocumentDocbook();

        try
        {
            $doc->loadFile( 
                __DIR__ . '/files/xhtml_sample_errnous.xml'
            );
        }
        catch ( ezcDocumentErroneousXmlException $e )
        {
            $errors = $e->getXmlErrors();

            $this->assertSame(
                2,
                count( $errors ),
                'Expected 2 XML errors.'
            );
        }

        $this->assertTrue(
            $doc->getDomDocument() instanceof DOMDocument,
            'DOMDocument not created properly'
        );
    }

    public function testLoadErroneousXmlDocumentSilent()
    {
        $doc = new ezcDocumentDocbook();
        $doc->options->failOnError = false;
        $doc->loadFile( 
            __DIR__ . '/files/xhtml_sample_errnous.xml'
        );

        $this->assertTrue(
            $doc->getDomDocument() instanceof DOMDocument,
            'DOMDocument not created properly'
        );
    }

    public function testSerializeXml()
    {
        $doc = new ezcDocumentDocbook();
        $doc->loadFile( 
            __DIR__ . '/files/xhtml_sample_basic.xml'
        );

        $this->assertEquals(
            file_get_contents( __DIR__ . '/files/xhtml_sample_basic.xml' ),
            $doc->save()
        );
    }

    public function testSerializeXmlFormat()
    {
        $doc = new ezcDocumentDocbook();
        $doc->options->indentXml = true;
        $doc->loadFile( 
            __DIR__ . '/files/xhtml_sample_basic.xml'
        );

        $this->assertEquals(
            file_get_contents( __DIR__ . '/files/xhtml_sample_basic_indented.xml' ),
            $doc->save()
        );
    }
}

?>
