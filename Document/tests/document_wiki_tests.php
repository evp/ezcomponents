<?php
/**
 * ezcDocumentConverterEzp3TpEzp4Tests
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
class ezcDocumentWikiTests extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testReadCreoleDocument()
    {
        $wiki = new ezcDocumentCreoleWiki();
        $wiki->loadFile( __DIR__ . '/files/wiki/creole/s_008_paragraphs.txt' );

        $docbook = $wiki->getAsDocbook();

        $this->assertEquals(
            file_get_contents( __DIR__ . '/files/wiki/creole/s_008_paragraphs.xml' ),
            $docbook->save(),
            'Document not visited as expected.'
        );
    }

    public function testWriteCreoleDocument()
    {
        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/files/wiki/creole/s_008_paragraphs.xml' );

        $wiki = new ezcDocumentCreoleWiki();
        $wiki->createFromDocbook( $docbook );

        $this->assertEquals(
            file_get_contents( __DIR__ . '/files/wiki/creole/s_008_paragraphs.txt' ),
            $wiki->save(),
            'Document not visited as expected.'
        );
    }

    public function testReadDokuwikiDocument()
    {
        $wiki = new ezcDocumentDokuwikiWiki();
        $wiki->loadFile( __DIR__ . '/files/wiki/dokuwiki/s_001_inline_markup.txt' );

        $docbook = $wiki->getAsDocbook();

        $this->assertEquals(
            file_get_contents( __DIR__ . '/files/wiki/dokuwiki/s_001_inline_markup.xml' ),
            $docbook->save(),
            'Document not visited as expected.'
        );
    }

    public function testWriteDokuwikiDocument()
    {
        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/files/wiki/dokuwiki/s_001_inline_markup.xml' );

        try
        {
            $wiki = new ezcDocumentDokuwikiWiki();
            $wiki->createFromDocbook( $docbook );
            $wiki->save();
            $this->fail( 'Expected ezcDocumentMissingVisitorException' );
        }
        catch ( ezcDocumentMissingVisitorException $e )
        { /* Expected */ }
    }

    public function testReadConfluenceDocument()
    {
        $wiki = new ezcDocumentConfluenceWiki();
        $wiki->loadFile( __DIR__ . '/files/wiki/confluence/s_002_inline_markup.txt' );

        $docbook = $wiki->getAsDocbook();

        $this->assertEquals(
            file_get_contents( __DIR__ . '/files/wiki/confluence/s_002_inline_markup.xml' ),
            $docbook->save(),
            'Document not visited as expected.'
        );
    }

    public function testWriteConfluenceDocument()
    {
        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/files/wiki/confluence/s_002_inline_markup.xml' );

        try
        {
            $wiki = new ezcDocumentConfluenceWiki();
            $wiki->createFromDocbook( $docbook );
            $wiki->save();
            $this->fail( 'Expected ezcDocumentMissingVisitorException' );
        }
        catch ( ezcDocumentMissingVisitorException $e )
        { /* Expected */ }
    }
}

?>
