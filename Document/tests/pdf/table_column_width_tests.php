<?php
/**
 * ezcDocumentPdfDriverHaruTests
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'driver_tests.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentPdfTableColumnWidthCalculatorTests extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public static function getTableColumnWidths()
    {
        return [['simple_tables.xml', '//doc:table[1]', [.314, .314, .372]], ['simple_tables.xml', '//doc:table[2]', [.317, .317, .366]], ['tables_with_list.xml', '//doc:table[1]', [.377, .623]], ['stacked_table.xml', '//doc:table[1]', [.236, .236, .528]], ['irregular_tables_1.xml', '//doc:table[1]', [.129, .871]], ['irregular_tables_2.xml', '//doc:table[1]', [.5, .5]]];
    }

    /**
     * @dataProvider getTableColumnWidths
     */
    public function testTableColumnWidthEstimation( $file, $query, $expectation )
    {
        $doc = new DOMDocument();
        $doc->load( __DIR__ . '/../files/pdf/' . $file );

        $xpath = new DOMXPath( $doc );
        $xpath->registerNamespace( 'doc', 'http://docbook.org/ns/docbook' );
        $table = $xpath->query( $query )->item( 0 );

        $calculator = new ezcDocumentPdfDefaultTableColumnWidthCalculator();
        $this->assertEquals(
            $expectation,
            $calculator->estimateWidths( $table ),
            'Wrong table width estimations',
            .001
        );
    }
}

?>
