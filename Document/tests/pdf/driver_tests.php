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

require_once 'base.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
abstract class ezcDocumentPdfDriverTests extends ezcDocumentPdfTestCase
{
    /**
     * Expected font widths for calculateWordWidth tests
     * 
     * @var array
     */
    protected $expectedWidths = ['testEstimateDefaultWordWidthWithoutPageCreation' => null, 'testEstimateDefaultWordWidth'                    => null, 'testEstimateWordWidthDifferentSize'              => null, 'testEstimateWordWidthDifferentSizeAndUnit'       => null, 'testEstimateBoldWordWidth'                       => null, 'testEstimateMonospaceWordWidth'                  => null, 'testFontStyleFallback'                           => null, 'testUtf8FontWidth'                               => null, 'testCustomFontWidthEstimation'                   => null];

    /**
     * Get driver to test
     * 
     * @return ezcDocumentPdfDriver
     */
    abstract protected function getDriver();

    public function testEstimateDefaultWordWidthWithoutPageCreation()
    {
        $driver = $this->getDriver();

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello' ),
            'Wrong word width estimation', .1
        );
    }

    public function testEstimateDefaultWordWidth()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello' ),
            'Wrong word width estimation', .1
        );
    }

    public function testEstimateWordWidthDifferentSize()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-size', '14' );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello' ),
            'Wrong word width estimation', .1
        );
    }

    public function testEstimateWordWidthDifferentSizeAndUnit()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-size', '14pt' );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello' ),
            'Wrong word width estimation', .1
        );
    }

    public function testEstimateBoldWordWidth()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-weight', 'bold' );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello' ),
            'Wrong word width estimation', .1
        );
    }

    public function testEstimateMonospaceWordWidth()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-family', 'monospace' );
        $driver->setTextFormatting( 'font-size', '12' );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello' ),
            'Wrong word width estimation', .1
        );
    }

    public function testFontStyleFallback()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-family', 'ZapfDingbats' );
        $driver->setTextFormatting( 'font-weight', 'bold' );
        $driver->setTextFormatting( 'font-style', 'italic' );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello' ),
            'Wrong word width estimation', .1
        );
    }

    public function testUtf8FontWidth()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'ℋℇℒℒΩ' ),
            'Wrong word width estimation', .1
        );
    }

    public function testCustomFontWidthEstimation()
    {
        $driver = $this->getDriver();

        try {
            $driver->registerFont(
                'my_font',
                ezcDocumentPdfDriver::FONT_PLAIN,
                [__DIR__ . '/../files/fonts/fdb_font.fdb', __DIR__ . '/../files/fonts/ps_font.pfb', __DIR__ . '/../files/fonts/font.ttf']
            );
        } catch ( ezcBaseFunctionalityNotSupportedException $e )
        {
            $this->markTestSkipped( 'Fonts are not supported.' );
        }

        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-family', 'my_font' );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->setTextFormatting( 'font-weight', 'bold' );

        $this->assertEquals(
            $this->expectedWidths[__FUNCTION__],
            $driver->calculateWordWidth( 'Hello world.' ),
            'Wrong word width estimation', .1
        );
    }

    public function testRenderHelloWorld()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-family', 'sans-serif' );
        $driver->setTextFormatting( 'font-size', '10' );

        $driver->drawWord( 0, 10, 'The quick brown fox jumps over the lazy dog' );
        $driver->drawWord( 0, 297, 'The quick brown fox jumps over the lazy dog' );
        $pdf = $driver->save();

        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderHelloWorldSmallFont()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-family', 'sans-serif' );
        $driver->setTextFormatting( 'font-size', '4' );

        $driver->drawWord( 0, 4, 'The quick brown fox jumps over the lazy dog' );
        $driver->drawWord( 0, 297, 'The quick brown fox jumps over the lazy dog' );
        $pdf = $driver->save();

        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderSwitchingFontStates()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-size', '8' );

        $driver->drawWord( 0, 8, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-weight', 'bold' );
        $driver->setTextFormatting( 'font-style', 'italic' );
        $driver->drawWord( 0, 18, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-style', 'normal' );
        $driver->drawWord( 0, 28, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-weight', 'normal' );
        $driver->drawWord( 0, 38, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-weight', 'bold' );
        $driver->drawWord( 0, 48, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-family', 'serif' );
        $driver->drawWord( 0, 58, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-weight', 'normal' );
        $driver->drawWord( 0, 68, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-family', 'Symbol' );
        $driver->drawWord( 0, 78, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-weight', 'bold' );
        $driver->drawWord( 0, 88, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-style', 'italic' );
        $driver->drawWord( 0, 98, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-family', 'monospace' );
        $driver->drawWord( 0, 108, 'The quick brown fox jumps over the lazy dog' );
        $driver->setTextFormatting( 'font-weight', 'bold' );
        $driver->setTextFormatting( 'font-style', 'italic' );
        $driver->drawWord( 0, 118, 'The quick brown fox jumps over the lazy dog' );
        $pdf = $driver->save();

        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderUtf8Text()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );

        $driver->drawWord( 10, 10, 'ℋℇℒℒΩ' );
        $pdf = $driver->save();

        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderPngImage()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );

        $driver->drawImage(
            __DIR__ . '/../files/pdf/images/logo-white.png', 'image/png',
            50, 50,
            ezcDocumentPcssMeasure::create( '113px' )->get(),
            ezcDocumentPcssMeasure::create( '57px' )->get()
        );
        $pdf = $driver->save();

        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderResizedJpegImage()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );

        $driver->drawImage(
            __DIR__ . '/../files/pdf/images/large.jpeg', 'image/jpeg',
            50, 50,
            110, 100
        );
        $pdf = $driver->save();

        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderColoredText()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $driver->setTextFormatting( 'font-family', 'sans-serif' );
        $driver->setTextFormatting( 'font-size', '4' );
        $color = new ezcDocumentPcssStyleColorValue();
        $color->parse( '#204a87' );
        $driver->setTextFormatting( 'color', $color->value );

        $driver->drawWord( 10, 10, 'The quick brown fox jumps over the lazy dog.' );
        $pdf = $driver->save();

        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderPolygon()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $color = new ezcDocumentPcssStyleColorValue();
        $color->parse( '#204a87' );

        $driver->drawPolygon(
            [[10, 10], [200, 10], [105, 287]],
            $color->value
        );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderPolylineClosed()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $color = new ezcDocumentPcssStyleColorValue();
        $color->parse( '#204a87' );

        $driver->drawPolyline(
            [[10, 10], [200, 10], [105, 287]],
            $color->value,
            1
        );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderPolylineOpen()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );
        $color = new ezcDocumentPcssStyleColorValue();
        $color->parse( '#204a87' );

        $driver->drawPolyline(
            [[200, 10], [105, 287], [10, 10]],
            $color->value,
            ezcDocumentPcssMeasure::create( '1pt' )->get(),
            false
        );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderLayeredPolygons()
    {
        $driver = $this->getDriver();
        $driver->createPage( 210, 297 );

        $color = new ezcDocumentPcssStyleColorValue();
        $color->parse( '#204a87' );
        $driver->drawPolygon(
            [[10, 10], [200, 10], [105, 287]],
            $color->value
        );

        $color = new ezcDocumentPcssStyleColorValue();
        $color->parse( '#2e3436' );
        $driver->drawPolyline(
            [[200, 287], [105, 10], [10, 287]],
            $color->value,
            1,
            false
        );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testAddExternalLink()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $driver->createPage( 100, 100 );

        $driver->addExternalLink( 0, 0, 100, 100, 'http://ezcomponents.org/' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testAddInternalLinkWithoutTarget()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $driver->createPage( 100, 100 );

        $driver->addInternalLink( 0, 0, 100, 50, 'my_target' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testAddInternalLinkAndTarget()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $driver->createPage( 100, 100 );

        $driver->addInternalLink( 0, 0, 100, 50, 'my_target' );
        $driver->addInternalLinkTarget( 'my_target' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testAddInternalLinkAndTargetOnNextPage()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $driver->createPage( 100, 100 );
        $driver->addInternalLink( 0, 0, 100, 50, 'my_target' );

        $driver->createPage( 100, 100 );
        $driver->addInternalLinkTarget( 'my_target' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderUnknownFont()
    {
        $driver = $this->getDriver();

        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        try {
            $driver->createPage( 250, 100 );
            $driver->setTextFormatting( 'font-family', 'my_font' );
            $this->fail( 'Expected ezcDocumentInvalidFontException.' );
        } catch ( ezcDocumentInvalidFontException $e )
        { /* Expected */ }
    }

    public function testRenderPlainTTFFont()
    {
        $driver = $this->getDriver();

        try {
            $driver->registerFont(
                'my_font',
                ezcDocumentPdfDriver::FONT_PLAIN,
                [__DIR__ . '/../files/fonts/font.ttf']
            );
        } catch ( ezcBaseFunctionalityNotSupportedException $e )
        {
            $this->markTestSkipped( 'Fonts are not supported.' );
        }

        $driver->createPage( 250, 100 );
        $driver->setTextFormatting( 'font-family', 'my_font' );
        $driver->setTextFormatting( 'font-size', '10' );

        $driver->drawWord( 0, 10, 'The quick brown fox jumps over the lazy dog' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderUnregisteredBoldTTFFont()
    {
        $driver = $this->getDriver();

        try {
            $driver->registerFont(
                'my_font',
                ezcDocumentPdfDriver::FONT_PLAIN,
                [__DIR__ . '/../files/fonts/font.ttf']
            );
        } catch ( ezcBaseFunctionalityNotSupportedException $e )
        {
            $this->markTestSkipped( 'Fonts are not supported.' );
        }

        $driver->createPage( 250, 100 );
        $driver->setTextFormatting( 'font-family', 'my_font' );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->setTextFormatting( 'font-weight', 'bold' );

        $driver->drawWord( 0, 10, 'The quick brown fox jumps over the lazy dog' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderRegisteredBoldTTFFont()
    {
        $driver = $this->getDriver();

        try {
            $driver->registerFont(
                'my_font',
                ezcDocumentPdfDriver::FONT_PLAIN,
                [__DIR__ . '/../files/fonts/font.ttf']
            );
            $driver->registerFont(
                'my_font',
                ezcDocumentPdfDriver::FONT_BOLD,
                [__DIR__ . '/../files/fonts/font2.ttf']
            );
        } catch ( ezcBaseFunctionalityNotSupportedException $e )
        {
            $this->markTestSkipped( 'Fonts are not supported.' );
        }

        $driver->createPage( 250, 100 );
        $driver->setTextFormatting( 'font-family', 'my_font' );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->setTextFormatting( 'font-weight', 'bold' );

        $driver->drawWord( 0, 10, 'The quick brown fox jumps over the lazy dog' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testRenderMultipleAlternativeFonts()
    {
        $driver = $this->getDriver();

        try {
            $driver->registerFont(
                'my_font',
                ezcDocumentPdfDriver::FONT_PLAIN,
                [__DIR__ . '/../files/fonts/fdb_font.fdb', __DIR__ . '/../files/fonts/ps_font.pfb', __DIR__ . '/../files/fonts/font.ttf']
            );
        } catch ( ezcBaseFunctionalityNotSupportedException $e )
        {
            $this->markTestSkipped( 'Fonts are not supported.' );
        }

        $driver->createPage( 250, 100 );
        $driver->setTextFormatting( 'font-family', 'my_font' );
        $driver->setTextFormatting( 'font-size', '10' );

        $driver->drawWord( 0, 10, 'The quick brown fox jumps over the lazy dog' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testSetDocumentMetaDataTitle()
    {
        $driver = $this->getDriver();

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document metadata test.' );

        $driver->setMetaData( 'title', 'Test document title' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testSetDocumentMetaDataAuthor()
    {
        $driver = $this->getDriver();

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document metadata test.' );

        $driver->setMetaData( 'author', 'Kore Nordmann' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testSetDocumentMetaDataSubject()
    {
        $driver = $this->getDriver();

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document metadata test.' );

        $driver->setMetaData( 'subject', 'Test document subject' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testSetDocumentMetaDataCreated()
    {
        $driver = $this->getDriver();

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document metadata test.' );

        $driver->setMetaData( 'created', date( 'r', 12345678 ) );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testSetDocumentMetaDataModified()
    {
        $driver = $this->getDriver();

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document metadata test.' );

        $driver->setMetaData( 'modified', date( 'r', 12345678 ) );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testGenerateCompressedPdf()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $options = new ezcDocumentPdfOptions();
        $options->compress = true;
        $driver->setOptions( $options );

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document compression test.' );

        $pdf = $driver->save();
        $this->assertPdfDocumentsSimilar( $pdf, get_class( $driver ) . '_' . __FUNCTION__ );
    }

    public function testGeneratePdfWithOwnerPassword()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $options = new ezcDocumentPdfOptions();
        $options->ownerPassword = 'foobar23';
        $driver->setOptions( $options );

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document compression test.' );

        $pdf = $driver->save();
        // We cannot make any proper asserstions here, since the PDF contents 
        // change with each regeneration because of the encryption
        $this->assertFalse( empty( $pdf ) );
    }

    public function testGenerateEncryptedPdf()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $options = new ezcDocumentPdfOptions();
        $options->ownerPassword = 'foobar23';
        $options->userPassword  = 'foobar';
        $driver->setOptions( $options );

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document compression test.' );

        $pdf = $driver->save();
        // We cannot make any proper asserstions here, since the PDF contents 
        // change with each regeneration because of the encryption
        $this->assertFalse( empty( $pdf ) );
    }

    public function testGenerateEncryptedProtectedPdf()
    {
        $driver = $this->getDriver();
        if ( $driver instanceof ezcDocumentPdfSvgDriver )
        {
            $this->markTestSkipped( 'Not supported by the SVG driver.' );
        }

        $options = new ezcDocumentPdfOptions();
        $options->permissions = 0;
        $options->ownerPassword = 'foobar23';
        $options->userPassword  = 'foobar';
        $driver->setOptions( $options );

        $driver->createPage( 100, 100 );
        $driver->setTextFormatting( 'font-size', '10' );
        $driver->drawWord( 0, 10, 'Document compression test.' );

        $pdf = $driver->save();
        // We cannot make any proper asserstions here, since the PDF contents 
        // change with each regeneration because of the encryption
        $this->assertFalse( empty( $pdf ) );
    }
}

?>
