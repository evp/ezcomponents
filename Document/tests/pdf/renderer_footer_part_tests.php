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
class ezcDocumentPdfRendererFooterPartTests extends ezcDocumentPdfTestCase
{
    protected $renderer;
    protected $docbook;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function setUp()
    {
        parent::setUp();

        $style = new ezcDocumentPcssStyleInferencer();
        $style->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['font-family'  => 'serif', 'text-columns' => '2', 'font-size'    => '10pt', 'line-height'  => '1']
        ), new ezcDocumentPcssLayoutDirective(
            ['title'],
            ['font-family'  => 'sans-serif', 'text-columns' => '2']
        ), new ezcDocumentPcssLayoutDirective(
            ['page'],
            ['page-size'    => 'A5']
        )] );

        $this->docbook = new ezcDocumentDocbook();
        $this->docbook->loadFile( __DIR__ . '/../files/pdf/long_text.xml' );

        $this->renderer = new ezcDocumentPdfMainRenderer(
            new ezcDocumentPdfSvgDriver(),
            $style
        );
    }

    public function testRenderDefaultFooter()
    {
        $this->renderer->registerPdfPart(
            new ezcDocumentPdfFooterPdfPart()
        );

        $pdf = $this->renderer->render(
            $this->docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );

        file_put_contents(
            $this->tempDir . ( $fileName = self::class . '_' . __FUNCTION__ . '.svg' ),
            $pdf
        );
    
        $this->assertXmlFileEqualsXmlFile(
            $this->basePath . 'renderer/' . $fileName,
            $this->tempDir . $fileName
        );
    }

    public function testRenderHeader()
    {
        $this->renderer->registerPdfPart(
            new ezcDocumentPdfFooterPdfPart( new ezcDocumentPdfFooterOptions( ['footer' => false] ) )
        );

        $pdf = $this->renderer->render(
            $this->docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );

        file_put_contents(
            $this->tempDir . ( $fileName = self::class . '_' . __FUNCTION__ . '.svg' ),
            $pdf
        );
    
        $this->assertXmlFileEqualsXmlFile(
            $this->basePath . 'renderer/' . $fileName,
            $this->tempDir . $fileName
        );
    }

    public function testRenderHeaderAndFooter()
    {
        $this->renderer->registerPdfPart(
            new ezcDocumentPdfFooterPdfPart( new ezcDocumentPdfFooterOptions( ['showDocumentTitle'  => false, 'showDocumentAuthor' => false, 'pageNumberOffset'   => 7, 'height'             => '10mm'] ) )
        );

        $this->renderer->registerPdfPart(
            new ezcDocumentPdfHeaderPdfPart( new ezcDocumentPdfFooterOptions( ['showPageNumber'   => false, 'height'           => '10mm'] ) )
        );

        $pdf = $this->renderer->render(
            $this->docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );

        file_put_contents(
            $this->tempDir . ( $fileName = self::class . '_' . __FUNCTION__ . '.svg' ),
            $pdf
        );
    
        $this->assertXmlFileEqualsXmlFile(
            $this->basePath . 'renderer/' . $fileName,
            $this->tempDir . $fileName
        );
    }
}

?>
