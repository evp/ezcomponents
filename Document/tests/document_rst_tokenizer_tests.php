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
class ezcDocumentRstTokenizerTests extends ezcTestCase
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
            $testFiles = glob( __DIR__ . '/files/rst/tokenizer/*.txt' );

            // Create array with the test file and the expected result file
            foreach ( $testFiles as $file )
            {
                self::$testDocuments[] = [$file, substr( $file, 0, -3 ) . 'tokens'];
            }
        }

        return self::$testDocuments;
    }

    /**
     * @dataProvider getTestDocuments
     */
    public function testTokenizeRstFile( $from, $to )
    {
        if ( !is_file( $to ) )
        {
            $this->markTestSkipped( "Comparision file '$to' not yet defined." );
        }

        $tokenizer = new ezcDocumentRstTokenizer();
        $tokens = $tokenizer->tokenizeFile( $from );

        $expected = include $to;

        // Store test file, to have something to compare on failure
        $tempDir = $this->createTempDir( 'rst_tokenizer_' ) . '/';
        file_put_contents( $tempDir . basename( $to ), "<?php\n\nreturn " . var_export( $tokens, true ) . ";\n\n" );

        $this->assertEquals(
            $expected,
            $tokens,
            'Extracted tokens do not match expected tokens.'
        );

        // Remove tempdir, when nothing failed.
        $this->removeTempDir();
    }

    public function testNotExistantFile()
    {
        try
        {
            $tokenizer = new ezcDocumentRstTokenizer();
            $tokens = $tokenizer->tokenizeFile(
                __DIR__ . '/files/rst/tokenizer/not_existant_file.txt'
            );
            $this->fail( 'Expected ezcBaseFileNotFoundException.' );
        }
        catch ( ezcBaseFileNotFoundException $e )
        { /* Expected */ }
    }
}

?>
