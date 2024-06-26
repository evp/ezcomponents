<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.1.3
 * @filesource
 * @package MvcTools
 * @subpackage Tests
 */
require_once 'MvcTools/tests/testfiles/testclasses.php';

/**
 * Test the handler classes.
 *
 * @package MvcTools
 * @subpackage Tests
 */
class ezcMvcToolsGzipResponseFilterTest extends ezcTestCase
{
    public function setUp()
    {
        if ( !extension_loaded( 'xdebug' ) )
        {
            self::markTestSkipped( "Xdebug is required." );
        }
    }

    public function testFilter()
    {
        $response = new ezcMvcResponse;
        $response->body = 'Ze Body';
        $filter = new ezcMvcGzipResponseFilter();
        $filter->filterResponse( $response );

        self::assertSame( 'gzip', $response->content->encoding );
        self::assertSame( gzencode( 'Ze Body' ), $response->body );
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( self::class );
    }
}
?>
