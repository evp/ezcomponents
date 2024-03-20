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
class ezcDocumentPdfMediaObjectRendererTests extends ezcDocumentPdfTestCase
{
    protected $document;
    protected $xpath;
    protected $styles;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testRenderMainSinglePage()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/image.xml',
            self::class . '_' . __FUNCTION__ . '.svg'
        );
    }

    public function testRenderInMultipleColumns()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/image.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            [new ezcDocumentPcssLayoutDirective(
                ['article'],
                ['text-columns' => '2', 'font-size'    => '10pt']
            ), new ezcDocumentPcssLayoutDirective(
                ['title'],
                ['text-columns' => '2']
            ), new ezcDocumentPcssLayoutDirective(
                ['page'],
                ['page-size'    => 'A5']
            )]
        );
    }

    public function testRenderLargeImage()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/image_large.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            []
        );
    }

    public function testRenderHighImage()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/image_high.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            []
        );
    }

    public function testRenderWrappedLargeImageAndWrappedText()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/image_wrapped.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            []
        );
    }
}

?>
