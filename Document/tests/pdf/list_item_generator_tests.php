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
class ezcDocumentPdfListItemGeneratorTests extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public static function getNumericGeneratorValues()
    {
        return [[1, '1'], [23, '23']];
    }

    /**
     * @dataProvider getNumericGeneratorValues
     */
    public function testNumericGenerator( $number, $item )
    {
        $generator = new ezcDocumentNumberedListItemGenerator();
        $this->assertSame(
            $item,
            $generator->getListItem( $number )
        );
    }

    public static function getAlphaGeneratorValues()
    {
        return [[1, 'a'], [23, 'w'], [27, 'aa']];
    }

    /**
     * @dataProvider getAlphaGeneratorValues
     */
    public function testAlphaGenerator( $number, $item )
    {
        $generator = new ezcDocumentAlphaListItemGenerator();
        $this->assertSame(
            $item,
            $generator->getListItem( $number )
        );
    }

    /**
     * @dataProvider getAlphaGeneratorValues
     */
    public function testAlphaUpperGenerator( $number, $item )
    {
        $generator = new ezcDocumentAlphaListItemGenerator( ezcDocumentAlphaListItemGenerator::UPPER );
        $this->assertSame(
            strtoupper( $item ),
            $generator->getListItem( $number )
        );
    }

    public static function getRomanGeneratorValues()
    {
        return [[1, 'i'], [3, 'iii'], [18, 'xviii'], [3999, 'mmmcmxcix'], [5999, 'mmmmmcmxcix']];
    }

    /**
     * @dataProvider getRomanGeneratorValues
     */
    public function testRomanGenerator( $number, $item )
    {
        $generator = new ezcDocumentRomanListItemGenerator();
        $this->assertSame(
            $item,
            $generator->getListItem( $number )
        );
    }

    /**
     * @dataProvider getRomanGeneratorValues
     */
    public function testRomanUpperGenerator( $number, $item )
    {
        $generator = new ezcDocumentRomanListItemGenerator( ezcDocumentRomanListItemGenerator::UPPER );
        $this->assertSame(
            strtoupper( $item ),
            $generator->getListItem( $number )
        );
    }

    public function testBulletGenerator()
    {
        $generator = new ezcDocumentBulletListItemGenerator();
        $this->assertSame( '-', $generator->getListItem( 23 ) );
        $this->assertSame( '-', $generator->getListItem( 42 ) );
    }

    public function testBulletGeneratorCusstomChar()
    {
        $generator = new ezcDocumentBulletListItemGenerator( '>' );
        $this->assertSame( '>', $generator->getListItem( 23 ) );
        $this->assertSame( '>', $generator->getListItem( 42 ) );
    }
}

?>
