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
class ezcDocumentPdfRenderRtlTests extends ezcDocumentPdfTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testRenderAllRtl()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/long_text.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            [new ezcDocumentPcssLayoutDirective(
                ['article'],
                ['direction' => 'rtl']
            )]
        );
    }

    public function testRenderParagraphRtl()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/long_text.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            [new ezcDocumentPcssLayoutDirective(
                ['para'],
                ['direction' => 'rtl']
            )]
        );
    }

    public function testRenderTitleRtl()
    {
        $this->renderFullDocument(
            __DIR__ . '/../files/pdf/long_text.xml',
            self::class . '_' . __FUNCTION__ . '.svg',
            [new ezcDocumentPcssLayoutDirective(
                ['title'],
                ['direction' => 'rtl']
            )]
        );
    }
}

?>
