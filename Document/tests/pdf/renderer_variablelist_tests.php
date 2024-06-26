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

require_once 'renderer_text_box_base_tests.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentPdfVariableListRendererTests extends ezcDocumentPdfTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function setUp()
    {
        parent::setUp();

        $this->styles = new ezcDocumentPcssStyleInferencer();
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['font-size' => '8mm']
        ), new ezcDocumentPcssLayoutDirective(
            ['page'],
            ['page-size' => 'TEST', 'margin'    => '0', 'padding'   => '10']
        )] );
    }

    public function testRenderDefinitionList()
    {
        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( "TTF" )
        );
        $mock->expects( $this->at( 2 ) )->method( 'drawWord' )->with(
            $this->equalTo( 15, 1. ), $this->equalTo( 29.2, 1. ), $this->equalTo( "TrueType" )
        );
        $mock->expects( $this->at( 3 ) )->method( 'drawWord' )->with(
            $this->equalTo( 51, 1. ), $this->equalTo( 29.2, 1. ), $this->equalTo( "fonts." )
        );
        $mock->expects( $this->at( 4 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 43.4, 1. ), $this->equalTo( "PFA" )
        );
        $mock->expects( $this->at( 5 ) )->method( 'drawWord' )->with(
            $this->equalTo( 15, 1. ), $this->equalTo( 55.4, 1. ), $this->equalTo( "PostScript" )
        );
        $mock->expects( $this->at( 6 ) )->method( 'drawWord' )->with(
            $this->equalTo( 59, 1. ), $this->equalTo( 55.4, 1. ), $this->equalTo( "fonts." )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/variablelist_short.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderDefinitionListWrapped()
    {
        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( "A" )
        );
        $mock->expects( $this->at( 4 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 29.2, 1. ), $this->equalTo( "is" )
        );
        $mock->expects( $this->at( 10 ) )->method( 'drawWord' )->with(
            $this->equalTo( 15, 1. ), $this->equalTo( 62.8, 1. ), $this->equalTo( "The" )
        );
        $mock->expects( $this->at( 20 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 21 ) )->method( 'drawWord' )->with(
            $this->equalTo( 15, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( "to" )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/variablelist_long.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }
}

?>
