<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.3
 * @filesource
 * @package Feed
 * @subpackage Tests
 */

include_once( 'UnitTest/src/regression_suite.php' );
include_once( 'UnitTest/src/regression_test.php' );
include_once( 'Feed/tests/regression_test.php' );

/**
 * @package Feed
 * @subpackage Tests
 */
class ezcFeedRss2RegressionGenerateTest extends ezcFeedRegressionTest
{
    public function __construct()
    {
        $basePath = __DIR__ . DIRECTORY_SEPARATOR . 'regression'
                                        . DIRECTORY_SEPARATOR . 'generate';

        $this->readDirRecursively( $basePath, $this->files, 'in' );

        parent::__construct();
    }

    public static function suite()
    {
        return new ezcTestRegressionSuite( self::class );
    }

    protected function cleanForCompare( $text )
    {
        $text = preg_replace( '@<pubDate>.*?</pubDate>@', '<pubDate>XXX</pubDate>', $text );
        $text = preg_replace( '@<lastBuildDate>.*?</lastBuildDate>@', '<lastBuildDate>XXX</lastBuildDate>', $text );
        $text = preg_replace( '@<generator.*?>.*?</generator>@', '<generator>XXX</generator>', $text );

        $text = preg_replace( '@<dc:date.*?>.*?</dc:date>@', '<dc:date>XXX</dc:date>', $text );
        return $text;
    }

    public function testRunRegression( $file )
    {
        $errors = [];

        $outFile = $this->outFileName( $file, '.in', '.out' );
        $expected = trim( file_get_contents( $outFile ) );
        $data = include_once( $file );
        $feed = $this->createFeed( 'rss2', $data );
        try
        {
            $generated = $feed->generate();
            $generated = trim( $this->cleanForCompare( $generated ) );
            $expected = $this->cleanForCompare( $expected );

        }
        catch ( ezcFeedException $e )
        {
            $generated = $e->getMessage();
        }

        $this->assertEquals( $expected, $generated, "The " . basename( $outFile ) . " is not the same as the generated feed from " . basename( $file ) . "." );
    }
}
?>
