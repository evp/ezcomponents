<?php
/**
 * ezcDocumentPcssParserTests
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
class ezcDocumentPcssParserTests extends ezcTestCase
{
    protected static $testDocuments = null;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public static function getTestDocuments()
    {
        if ( self::$testDocuments === null )
        {
            // Get a list of all test files from the respektive folder
            $testFiles = glob( __DIR__ . '/../files/pcss/s_*.pcss' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$testDocuments[] = [$file, substr( $file, 0, -4 ) . 'ast'];
            }
        }

        return self::$testDocuments;
        return array_slice( self::$testDocuments, -1, 1 );
    }

    /**
     * @dataProvider getTestDocuments
     */
    public function testParsePdfCssFile( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $parser     = new ezcDocumentPcssParser();
        $directives = $parser->parseFile( $from );

        // Change file locations to something not depending on the current test
        // env
        foreach ( $directives as $directive )
        {
            $directive->file = basename( $directive->file );
        }

        $expected = include $to;

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'pcss_parser_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), "<?php\n\nreturn " . var_export( $directives, true ) . ";\n\n" );

        $this->assertEquals(
            $expected,
            $directives,
            'Parsed document does not match expected document.',
            0, 20
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    public static function getErroneousTestDocuments()
    {
//        return array();
        return [[__DIR__ . '/../files/pcss/e_001_missing_address.pcss', 'Parse error: Fatal error: \'Expected one of: T_ADDRESS (CSS element addressing queries), T_DESC_ADDRESS (CSS element addressing queries), T_ADDRESS_ID (CSS element addressing queries), T_ADDRESS_CLASS (CSS element addressing queries), found T_START ("{").\' in file \'$file\' in line 1 at position 2.'], [__DIR__ . '/../files/pcss/e_002_invalid_address.pcss', "Parse error: Fatal error: 'Could not parse string: '0123\n' in state: default.' in file '\$file' in line 1 at position 1."], [__DIR__ . '/../files/pcss/e_003_missing_start.pcss', 'Parse error: Fatal error: \'Expected one of: T_ADDRESS (CSS element addressing queries), T_DESC_ADDRESS (CSS element addressing queries), T_ADDRESS_ID (CSS element addressing queries), T_ADDRESS_CLASS (CSS element addressing queries), found T_FORMATTING (formatting specification).\' in file \'$file\' in line 2 at position 11.'], [__DIR__ . '/../files/pcss/e_004_missing_end.pcss', 'Parse error: Fatal error: \'Expected one of: T_FORMATTING (formatting specification), found T_EOF (end of file).\' in file \'$file\' in line 3 at position 1.'], [__DIR__ . '/../files/pcss/e_005_missing_end_2.pcss', 'Parse error: Fatal error: \'Expected one of: T_FORMATTING (formatting specification), found T_ADDRESS (CSS element addressing queries).\' in file \'$file\' in line 4 at position 5.'], [__DIR__ . '/../files/pcss/e_006_invalid_rule.pcss', "Parse error: Fatal error: 'Could not parse string: ';\n}\n' in state: default.' in file '\$file' in line 2 at position 8."]];
    }

    /**
     * @dataProvider getErroneousTestDocuments
     */
    public function testParseErroneousPdfCssFile( $file, $message )
    {
        $parser = new ezcDocumentPcssParser();

        try
        {
            $directives = $parser->parseFile( $file );
            $this->fail( 'Expected ezcDocumentPcssParserException.' );
        }
        catch ( ezcDocumentParserException $e )
        {
            $this->assertSame(
                $message,
                preg_replace( '(in file \'[^\']+\')', 'in file \'$file\'', $e->getMessage() ),
                'Different parse error expected.'
            );
        }
    }
}

?>
