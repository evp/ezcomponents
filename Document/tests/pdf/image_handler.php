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
class ezcDocumentPdfImageHandlerTests extends ezcDocumentPdfTestCase
{
    protected $document;
    protected $xpath;
    protected $styles;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testImageHandler()
    {
        $image = ezcDocumentPdfImage::createFromFile( __DIR__ . '/../files/pdf/images/logo-white.png' );

        $this->assertSame(
            'image/png',
            $image->getMimeType()
        );
        $this->assertEquals(
            [new ezcDocumentPcssMeasure( '113px' ), new ezcDocumentPcssMeasure( '57px' )],
            $image->getDimensions()
        );
    }

    public static function provideCanHandleData()
    {
        return [['files/pdf/images/logo-white.eps', false], ['files/pdf/images/logo-white.pdf', false], ['files/pdf/images/logo-white.png', true], ['files/pdf/images/logo-white.svg', false], ['files/pdf/images/logo-white.jpeg', true]];
    }

    /**
     * @dataProvider provideCanHandleData
     */
    public function testCanHandleImageType( $image, $return )
    {
        $handler = new ezcDocumentPdfPhpImageHandler();
        $this->assertSame( $return, $handler->canHandle( __DIR__ . '/../' . $image ) );
    }

    public static function provideDimensionData()
    {
        return [['files/pdf/images/logo-white.eps', false], ['files/pdf/images/logo-white.pdf', false], ['files/pdf/images/logo-white.png', [new ezcDocumentPcssMeasure( '113px' ), new ezcDocumentPcssMeasure( '57px' )]], ['files/pdf/images/logo-white.svg', false], ['files/pdf/images/logo-white.png', [new ezcDocumentPcssMeasure( '113px' ), new ezcDocumentPcssMeasure( '57px' )]]];
    }

    /**
     * @dataProvider provideDimensionData
     */
    public function testImageDimensions( $image, $return )
    {
        $handler = new ezcDocumentPdfPhpImageHandler();
        $this->assertEquals( $return, $handler->getDimensions( __DIR__ . '/../' . $image ) );
    }

    public static function provideMimeTypeData()
    {
        return [['files/pdf/images/logo-white.eps', false], ['files/pdf/images/logo-white.pdf', false], ['files/pdf/images/logo-white.png', 'image/png'], ['files/pdf/images/logo-white.svg', false], ['files/pdf/images/logo-white.jpeg', 'image/jpeg']];
    }

    /**
     * @dataProvider provideMimeTypeData
     */
    public function testImageMimeType( $image, $return )
    {
        $handler = new ezcDocumentPdfPhpImageHandler();
        $this->assertSame( $return, $handler->getMimeType( __DIR__ . '/../' . $image ) );
    }
}

?>
