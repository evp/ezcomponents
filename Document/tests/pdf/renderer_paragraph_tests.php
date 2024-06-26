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
class ezcDocumentPdfParagraphRendererTests extends ezcDocumentPdfTextBoxRendererBaseTests
{
    /**
     * Renderer used for the tests
     * 
     * @var string
     */
    protected $renderer = 'ezcDocumentPdfWrappingTextBoxRenderer';

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testRenderParagraphSplitting()
    {
        // Additional formatting

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'This' )
        );
        $mock->expects( $this->at( 24 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 25 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'short' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/wrapping.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphWidow()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['orphans' => '0', 'widows'  => '0']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'This' )
        );
        $mock->expects( $this->at( 24 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 25 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'the' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/widow.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphWidowWrap()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['orphans' => '0', 'widows'  => '2']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'This' )
        );
        $mock->expects( $this->at( 21 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 22 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'widow,' )
        );
        $mock->expects( $this->at( 25 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 29.2, 1. ), $this->equalTo( 'the' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/widow.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphOrphan()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['orphans' => '0', 'widows'  => '0']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'This' )
        );
        $mock->expects( $this->at( 22 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 82, 1. ), $this->equalTo( 'Second' )
        );
        $mock->expects( $this->at( 24 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 25 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'which' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/orphan.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphOrphanWrapped()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['orphans' => '2', 'widows'  => '0']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'This' )
        );
        $mock->expects( $this->at( 22 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 23 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'Second' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/orphan.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphOrphansAndWidows()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['orphans' => '3', 'widows'  => '3']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['createPage', 'drawWord'] );

        // Expectations
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'This' )
        );
        $mock->expects( $this->at( 22 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 23 ) )->method( 'drawWord' )->with(
            $this->equalTo( 10, 1. ), $this->equalTo( 18, 1. ), $this->equalTo( 'Second' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/short_orphan.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphSplittingWithBorderFirstPage()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['para'],
            ['line-height'      => '1', 'padding'          => '5', 'margin'           => '5', 'border'           => '1mm solid #A00000', 'background-color' => '#eedbdb']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['drawPolyline', 'drawPolygon', 'createPage', 'drawWord'] );

        // Expectations for first page
        $mock->expects( $this->at( 0 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 1 ) )->method( 'drawPolygon' )->with(
            $this->equalTo( [[15, 15], [85, 15], [85, 83], [15, 83]], 1. ),
            $this->equalTo( ['red'   => .93, 'green' => .86, 'blue'  => .86, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 2 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[15.5, 15.5], [15.5, 82.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 3 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[15.5, 15.5], [84.5, 15.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 4 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[84.5, 15.5], [84.5, 82.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 5 ) )->method( 'drawWord' )->with(
            $this->equalTo( 21, 1. ), $this->equalTo( 29, 1. ), $this->equalTo( 'This' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/wrapping.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphSplittingWithBorderSecondPage()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['para'],
            ['line-height'      => '1', 'padding'          => '5', 'margin'           => '5', 'border'           => '1mm solid #A00000', 'background-color' => '#eedbdb']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['drawPolyline', 'drawPolygon', 'createPage', 'drawWord'] );

        // Expectations for second page
        $mock->expects( $this->at( 18 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 19 ) )->method( 'drawPolygon' )->with(
            $this->equalTo( [[15, 15], [85, 15], [85, 83], [15, 83]], 1. ),
            $this->equalTo( ['red'   => .93, 'green' => .86, 'blue'  => .86, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 20 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[15.5, 15.5], [15.5, 82.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 21 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[84.5, 15.5], [84.5, 82.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 22 ) )->method( 'drawWord' )->with(
            $this->equalTo( 21, 1. ), $this->equalTo( 29, 1. ), $this->equalTo( 'exceeding' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/wrapping.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }

    public function testRenderParagraphSplittingWithBorderLastPage()
    {
        // Additional formatting
        $this->styles->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['para'],
            ['line-height'      => '1', 'padding'          => '5', 'margin'           => '5', 'border'           => '1mm solid #A00000', 'background-color' => '#eedbdb']
        )] );

        $mock = $this->getMock( 'ezcTestDocumentPdfMockDriver', ['drawPolyline', 'drawPolygon', 'createPage', 'drawWord'] );

        // Expectations for third page
        $mock->expects( $this->at( 38 ) )->method( 'createPage' )->with(
            $this->equalTo( 100, 1. ), $this->equalTo( 100, 1. )
        );
        $mock->expects( $this->at( 39 ) )->method( 'drawPolygon' )->with(
            $this->equalTo( [[15, 15], [85, 15], [85, 51], [15, 51]], 1. ),
            $this->equalTo( ['red'   => .93, 'green' => .86, 'blue'  => .86, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 40 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[15.5, 15.5], [15.5, 50.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 41 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[84.5, 15.5], [84.5, 50.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 42 ) )->method( 'drawPolyline' )->with(
            $this->equalTo( [[84.5, 50.5], [15.5, 50.5]], .1 ),
            $this->equalTo( ['red'   => .63, 'green' => .0, 'blue'  => .0, 'alpha' => 0], .01 )
        );
        $mock->expects( $this->at( 43 ) )->method( 'drawWord' )->with(
            $this->equalTo( 21, 1. ), $this->equalTo( 29, 1. ), $this->equalTo( 'be' )
        );

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/wrapping.xml' );

        $renderer  = new ezcDocumentPdfMainRenderer( $mock, $this->styles );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );
    }
}

?>
