<?php
/**
 * ezcDocumentPdfDriverTcpdfTests
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once 'base.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentPdfTableRendererTests extends ezcDocumentPdfTestCase
{
    protected $document;
    protected $xpath;
    protected $styles;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public static function getTableDocuments()
    {
        return [[__DIR__ . '/../files/pdf/simple_tables.xml'], [__DIR__ . '/../files/pdf/tables_with_list.xml'], [__DIR__ . '/../files/pdf/stacked_table.xml'], [__DIR__ . '/../files/pdf/wrapped_table.xml'], [__DIR__ . '/../files/pdf/irregular_tables_1.xml'], [__DIR__ . '/../files/pdf/irregular_tables_2.xml'], [__DIR__ . '/../files/pdf/irregular_tables_3.xml']];
    }

    /**
     * @dataProvider getTableDocuments
     */
    public function testRenderTables( $file )
    {
        $this->renderFullDocument( $file, self::class . '_' . basename( $file, '.xml' ) . '.svg', [] );
    }
}

?>
