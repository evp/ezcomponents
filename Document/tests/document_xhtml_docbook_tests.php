<?php
/**
 * ezcDocumentRstParserTests
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'helper/rst_dummy_directives.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentXhtmlDocbookTests extends ezcTestCase
{
    protected static $rstTestDocuments = null;
    protected static $metadataTestDocuments = null;
    protected static $badTestDocuments = null;
    protected static $tableTestDocuments = null;
    protected static $testDocuments = null;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testCreateFromDocbook()
    {
        $from = __DIR__ . '/files/docbook/xhtml/s_001_empty.xml';
        $to   = __DIR__ . '/files/docbook/xhtml/s_001_empty.html';

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( $from );

        $document = new ezcDocumentXhtml();
        $document->createFromDocbook( $docbook );

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'docbook_xhtml_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml = $document->save() );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml,
            'Document not visited as expected.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    public static function getRstTestDocuments()
    {
        if ( self::$rstTestDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( __DIR__ . '/files/xhtml/rst_html/s_*.html' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$rstTestDocuments[] = [$file, substr( $file, 0, -4 ) . 'xml'];
            }
        }

        return self::$rstTestDocuments;
        return array_slice( self::$rstTestDocuments, 0, 10 );
    }

    public static function getMetadataTestDocuments()
    {
        if ( self::$metadataTestDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( __DIR__ . '/files/xhtml/metadata/s_*.html' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$metadataTestDocuments[] = [$file, substr( $file, 0, -4 ) . 'xml'];
            }
        }

        return self::$metadataTestDocuments;
        return array_slice( self::$metadataTestDocuments, 0, 0 );
    }

    public static function getBadTestDocuments()
    {
        if ( self::$badTestDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( __DIR__ . '/files/xhtml/bad_markup/s_*.html' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$badTestDocuments[] = [$file, substr( $file, 0, -4 ) . 'xml'];
            }
        }

        return self::$badTestDocuments;
        return array_slice( self::$badTestDocuments, 0, 0 );
    }

    public static function getTableTestDocuments()
    {
        if ( self::$tableTestDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( __DIR__ . '/files/xhtml/table/s_*.html' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$tableTestDocuments[] = [$file, substr( $file, 0, -4 ) . 'xml'];
            }
        }

        return self::$tableTestDocuments;
        return array_slice( self::$tableTestDocuments, 0, 0 );
    }

    public static function getTestDocuments()
    {
        if ( self::$testDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( __DIR__ . '/files/xhtml/tests/s_*.html' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$testDocuments[] = [$file, substr( $file, 0, -4 ) . 'xml'];
            }
        }

        return self::$testDocuments;
        return array_slice( self::$tableTestDocuments, 0, 0 );
    }

    /**
     * @dataProvider getRstTestDocuments
     */
    public function testParseRstHtmlFile( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $document = new ezcDocumentXhtml();
        $document->loadFile( $from );

        $docbook = $document->getAsDocbook();
        $xml = $docbook->save();

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'xhtml_rst_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml );

        // Validate generated docbook
        $this->assertTrue( $docbook->validateString( $xml ) );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml,
            'Document not visited as expected.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    /**
     * @dataProvider getMetadataTestDocuments
     */
    public function testExtractMetadata( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $document = new ezcDocumentXhtml();
        $document->loadFile( $from );

        $docbook = $document->getAsDocbook();
        $xml = $docbook->save();

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'xhtml_metadata_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml );

        // Validate generated docbook
        $this->assertTrue( $docbook->validateString( $xml ) );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml,
            'Document not visited as expected.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    /**
     * @dataProvider getBadTestDocuments
     */
    public function testConvertBadMarkup( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $document = new ezcDocumentXhtml();
        $document->setFilters( [new ezcDocumentXhtmlElementFilter(), new ezcDocumentXhtmlMetadataFilter(), new ezcDocumentXhtmlContentLocatorFilter()] );
        $document->loadFile( $from );

        $docbook = $document->getAsDocbook();
        $xml = $docbook->save();

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'xhtml_bad_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml );

        // Do not validate the converted "bad" markup.
        // $this->assertTrue( $docbook->validateString( $xml ) );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml,
            'Document not visited as expected.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    public function testXpathFilter()
    {
        $from = __DIR__ . '/files/xhtml/xpath/s_004_detect_url_in_texts.html';
        $to   = __DIR__ . '/files/xhtml/xpath/s_004_detect_url_in_texts.xml';

        $document = new ezcDocumentXhtml();
        $document->setFilters( [new ezcDocumentXhtmlXpathFilter(
            '//div[@class = "content"]'
        ), new ezcDocumentXhtmlElementFilter(), new ezcDocumentXhtmlMetadataFilter()] );
        $document->loadFile( $from );

        $docbook = $document->getAsDocbook();
        $xml = $docbook->save();

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'xpath_filter_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml );

        // Do not validate the converted "bad" markup.
        // $this->assertTrue( $docbook->validateString( $xml ) );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml,
            'Document not visited as expected.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    /**
     * @dataProvider getTableTestDocuments
     */
    public function testTableDetector( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $document = new ezcDocumentXhtml();
        $document->setFilters( [new ezcDocumentXhtmlElementFilter(), new ezcDocumentXhtmlMetadataFilter(), new ezcDocumentXhtmlContentLocatorFilter(), new ezcDocumentXhtmlTablesFilter()] );
        $document->loadFile( $from );

        $docbook = $document->getAsDocbook();
        $xml = $docbook->save();

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'xhtml_table_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml );

        // Do not validate the converted "bad" markup.
        // $this->assertTrue( $docbook->validateString( $xml ) );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml,
            'Document not visited as expected.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    /**
     * @dataProvider getTestDocuments
     */
    public function testCommonConversions( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $document = new ezcDocumentXhtml();
        $document->setFilters( [new ezcDocumentXhtmlElementFilter(), new ezcDocumentXhtmlMetadataFilter(), new ezcDocumentXhtmlTablesFilter()] );
        $document->loadFile( $from );

        $docbook = $document->getAsDocbook();
        $xml = $docbook->save();

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'xhtml_tests_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), $xml );

        $this->assertTrue( $docbook->validateString( $xml ) );

        $this->assertEquals(
            file_get_contents( $to ),
            $xml,
            'Document not visited as expected.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }
}

?>
