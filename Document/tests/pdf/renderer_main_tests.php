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
class ezcDocumentPdfMainRendererTests extends ezcDocumentPdfTestCase
{
    protected $document;
    protected $xpath;
    protected $styles;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testRenderUnknownElements()
    {
        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/unknown.xml' );

        try {
            $renderer  = new ezcDocumentPdfMainRenderer(
                new ezcDocumentPdfSvgDriver(),
                new ezcDocumentPcssStyleInferencer()
            );

            $pdf = $renderer->render(
                $docbook,
                new ezcDocumentPdfDefaultHyphenator()
            );
            $this->fail( 'Expected ezcDocumentVisitException.' );
        }
        catch ( ezcDocumentVisitException $e )
        { /* Expected */ }
    }

    public function testRenderUnknownElementsSilence()
    {
        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/unknown.xml' );

        $options  = new ezcDocumentPdfOptions();
        $options->errorReporting = E_PARSE;
        $renderer = new ezcDocumentPdfMainRenderer(
            new ezcDocumentPdfSvgDriver(),
            new ezcDocumentPcssStyleInferencer(),
            $options
        );

        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );

        $errors = $renderer->getErrors();
        $this->assertEquals( 1, count( $errors ) );
        $this->assertEquals(
            'Visitor error: Notice: \'Unknown and unhandled element: http://example.org/unknown:article.\' in line 0 at position 0.',
            reset( $errors )->getMessage()
        );
    }

    public function testRenderMainSinglePage()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/long_text.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            []
        );
    }

    public function testRenderMainSinglePageNotNamespaced()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/paragraph_nons.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            []
        );
    }

    public function testRenderMainMulticolumnLayout()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/long_text.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            [new ezcDocumentPcssLayoutDirective(
                ['article'],
                ['text-columns' => '3', 'line-height'  => '1']
            )]
        );
    }

    public function testRenderLongTextParagraphConflict()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/test_long_wrapping.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            []
        );
    }

    public function testRenderLongTextWithInternalLinks()
    {
        if ( !ezcBaseFeatures::hasExtensionSupport( 'haru' ) )
        {
            $this->markTestSkipped( 'This test requires pecl/haru installed.' );
        }

        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/internal_links.xml' );

        $style = new ezcDocumentPcssStyleInferencer();
        $style->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['page'],
            ['page-size' => 'A6']
        )] );

        $renderer  = new ezcDocumentPdfMainRenderer(
            new ezcDocumentPdfHaruDriver(),
            $style
        );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );

        $this->assertPdfDocumentsSimilar( $pdf, self::class . '_' . __FUNCTION__ );
    }

    public function testRenderUnavailableCustomFont()
    {
        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/wrapping.xml' );

        $style = new ezcDocumentPcssStyleInferencer();
        $style->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['font-family' => 'my-font']
        )] );

        $renderer  = new ezcDocumentPdfMainRenderer(
            new ezcDocumentPdfSvgDriver(),
            $style
        );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );

        $this->assertPdfDocumentsSimilar( $pdf, self::class . '_' . __FUNCTION__ );
    }

    public function testRenderCustomFont()
    {
        $docbook = new ezcDocumentDocbook();
        $docbook->loadFile( __DIR__ . '/../files/pdf/wrapping.xml' );

        $style = new ezcDocumentPcssStyleInferencer();
        $style->appendStyleDirectives( [new ezcDocumentPcssLayoutDirective(
            ['article'],
            ['font-family' => 'my-font']
        ), new ezcDocumentPcssDeclarationDirective(
            '@font-face',
            ['font-family' => 'my-font', 'src'         => 'url( ' . __DIR__ . '/../files/fonts/font.ttf )']
        )] );

        $renderer  = new ezcDocumentPdfMainRenderer(
            new ezcDocumentPdfSvgDriver(),
            $style
        );
        $pdf = $renderer->render(
            $docbook,
            new ezcDocumentPdfDefaultHyphenator()
        );

        $this->assertPdfDocumentsSimilar( $pdf, self::class . '_' . __FUNCTION__ );
    }
}

?>
