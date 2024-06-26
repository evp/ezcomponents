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

/**
 * @package Feed
 * @subpackage Tests
 */
class ezcFeedRss1RegressionParseTest extends ezcTestRegressionTest
{
    public function __construct()
    {
        $basePath = __DIR__ . DIRECTORY_SEPARATOR . 'regression'
                                        . DIRECTORY_SEPARATOR . 'parse';

        $this->readDirRecursively( $basePath, $this->files, 'in' );

        parent::__construct();
    }

    public static function suite()
    {
        return new ezcTestRegressionSuite( self::class );
    }

    protected function cleanForCompare( $expected, $parsed )
    {
        $referenceDate = new DateTime();

        if ( isset( $parsed->DublinCore )
             && isset( $parsed->DublinCore->date )
             && is_array( $parsed->DublinCore->date ) )
        {
            foreach ( $parsed->DublinCore->date as $date )
            {
                $date->date = $referenceDate;
            }
        }

        if ( isset( $expected->DublinCore )
             && isset( $expected->DublinCore->date )
             && is_array( $expected->DublinCore->date ) )
        {
            foreach ( $expected->DublinCore->date as $date )
            {
                $date->date = $referenceDate;
            }
        }

        $this->cleanDate( $parsed, 'updated', $referenceDate );
        $this->cleanDate( $expected, 'updated', $referenceDate );
    }

    protected function cleanDate( $feed, $element, $newDate )
    {
        if ( isset( $feed->item ) )
        {
            foreach ( $feed->item as $item )
            {
                if ( isset( $item->DublinCore )
                     && isset( $item->DublinCore->date )
                     && is_array( $item->DublinCore->date ) )
                {
                    foreach ( $item->DublinCore->date as $date )
                    {
                        $date->date = $newDate;
                    }
                }
            }
        }
    }

    public function testRunRegression( $file )
    {
        $errors = [];

        $outFile = $this->outFileName( $file, '.in', '.out' );

        try
        {
            $parsed = ezcFeed::parseContent( file_get_contents( $file ) );
            $expected = include_once( $outFile );
            $this->cleanForCompare( $expected, $parsed );
        }
        catch ( ezcFeedException $e )
        {
            $parsed = $e->getMessage();
            $expected = trim( file_get_contents( $outFile ) );
        }
        $this->assertEquals( var_export( $expected, true ), var_export( $parsed, true ), "The " . basename( $outFile ) . " is not the same as the parsed feed from " . basename( $file ) . "." );
    }
}
?>
