<?php
/**
 * ezcDocumentOdtFormattingPropertiesTest.
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentOdtPcssConvertersTest extends ezcTestCase
{
    protected $domElement;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $domDocument = new DOMDocument();
        $this->domElement = $domDocument->appendChild(
            $domDocument->createElement( 'parent' )
        );
    }

    protected function assertAttributesCorrect( array $expectedAttributes )
    {
        $this->assertEquals(
            count( $expectedAttributes ),
            $this->domElement->attributes->length,
            'Inconsistent number of text property element attributes.'
        );

        foreach ( $expectedAttributes as $attrDef )
        {
            $this->assertTrue(
                $this->domElement->hasAttributeNS(
                    $attrDef[0],
                    $attrDef[1]
                ),
                "Missing attribute '{$attrDef[0]}:{$attrDef[1]}'."
            );
            $this->assertEquals(
                $attrDef[2],
                ( $actAttrVal = $this->domElement->getAttributeNS(
                    $attrDef[0],
                    $attrDef[1]
                ) ),
                "Attribute '{$attrDef[0]}:{$attrDef[1]}' has incorrect value '$actAttrVal', expected '{$attrDef[2]}'."
            );
        }
    }

    /**
     * @dataProvider getTextDecorationTestSets
     */
    public function testConvertTextDecoration( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtPcssTextDecorationConverter();
        $converter->convert( $this->domElement, 'text-decoration', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    /**
     * Test sets for the 'text-decoration' style attribute.
     */
    public static function getTextDecorationTestSets()
    {
        return ['line-through' => [
            // style
            new ezcDocumentPcssStyleListValue( ['line-through'] ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-line-through-type', 'single'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-line-through-style', 'solid'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-line-through-width', 'auto'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-line-through-color', 'font-color'],
            ],
        ], 'underline' => [
            // style
            new ezcDocumentPcssStyleListValue( ['underline'] ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-type', 'single'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-style', 'solid'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-width', 'auto'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-color', 'font-color'],
            ],
        ], 'overline' => [
            // style
            new ezcDocumentPcssStyleListValue( ['overline'] ),
            // expected attributes
            [],
        ], 'blink' => [
            // style
            new ezcDocumentPcssStyleListValue( ['blink'] ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-blinking', 'true'],
            ],
        ], 'multiple' => [
            // style
            new ezcDocumentPcssStyleListValue( ['blink', 'underline'] ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-blinking', 'true'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-type', 'single'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-style', 'solid'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-width', 'auto'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'text-underline-color', 'font-color'],
            ],
        ]];
    }

    /**
     * @dataProvider getColorTestSets
     */
    public function testConvertColor( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtPcssColorConverter();
        $converter->convert( $this->domElement, 'color', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    /**
     * Test sets for color style attributes.
     */
    public static function getColorTestSets()
    {
        return ['non-transparent' => [
            // style
            new ezcDocumentPcssStyleColorValue(
                ['red'   => 1.0, 'green' => 1.0, 'blue'  => 1.0, 'alpha' => 0.4]
            ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'color', '#ffffff'],
            ],
        ], 'transparent' => [
            // style
            new ezcDocumentPcssStyleColorValue(
                ['red'   => 1.0, 'green' => 1.0, 'blue'  => 1.0, 'alpha' => 0.5]
            ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'color', 'transparent'],
            ],
        ], 'value' => [
            // style
            new ezcDocumentPcssStyleColorValue(
                ['red'   => 0.75294117647059, 'green' => 1.0, 'blue'  => 0, 'alpha' => 0.0]
            ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'color', '#c0ff00'],
            ],
        ]];
    }

    /**
     * @dataProvider getBackgroundColorTestSets
     */
    public function testConvertBackgroundColor( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtPcssColorConverter();
        $converter->convert( $this->domElement, 'background-color', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    /**
     * Test sets for background-color style attributes.
     */
    public static function getBackgroundColorTestSets()
    {
        // Re-use color test sets, but with background-color attribute name
        $colorTestSets = self::getColorTestSets();
        foreach ( $colorTestSets as $setId => $set )
        {
            foreach( $set[1] as $attrId => $attrDef )
            {
                $attrDef[1] = 'background-color';
                $colorTestSets[$setId][1][$attrId] = $attrDef;
            }
        }
        return $colorTestSets;
    }

    /**
     * @dataProvider getFontSizeTestSets
     */
    public function testConvertFontSize( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtPcssFontSizeConverter();
        $converter->convert( $this->domElement, 'font-size', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    /**
     * Test sets for font style attributes.
     */
    public static function getFontSizeTestSets()
    {
        return ['font-size' => [
            // styles
            new ezcDocumentPcssStyleMeasureValue( 23 ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'font-size', '23mm'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'font-size-asian', '23mm'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'font-size-complex', '23mm'],
            ],
        ]];
    }

    /**
     * @dataProvider getTextFontNameTestSets
     */
    public function testConvertMiscFontProperty( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtPcssFontNameConverter();
        $converter->convert( $this->domElement, 'font-name', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    public static function getTextFontNameTestSets()
    {
        return ['font-name' => [
            // styles
            new ezcDocumentPcssStyleStringValue( 'DejaVu Sans' ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_STYLE, 'font-name', 'DejaVu Sans'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'font-name-asian', 'DejaVu Sans'],
                [ezcDocumentOdt::NS_ODT_STYLE, 'font-name-complex', 'DejaVu Sans'],
            ],
        ]];
    }

    /**
     * @dataProvider getTextAlignTestSets
     */
    public function testConvertMiscProperty( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtDefaultPcssConverter();
        $converter->convert( $this->domElement, 'text-align', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    public static function getTextAlignTestSets()
    {
        return [[
            // style
            new ezcDocumentPcssStyleStringValue( 'center' ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'text-align', 'center'],
            ],
        ]];
    }

    /**
     * @dataProvider getMarginTestSets
     */
    public function testConvertMarginProperty( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtPcssMarginConverter();
        $converter->convert( $this->domElement, 'margin', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    /**
     * Test sets for the 'margin' style attribute.
     */
    public static function getMarginTestSets()
    {
        return ['margin full' => [
            // style
            new ezcDocumentPcssStyleMeasureBoxValue(
                ['top'    => 1, 'left'   => 2, 'bottom' => 3, 'right'  => 4]
            ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'margin-top', '1mm'],
                [ezcDocumentOdt::NS_ODT_FO, 'margin-left', '2mm'],
                [ezcDocumentOdt::NS_ODT_FO, 'margin-bottom', '3mm'],
                [ezcDocumentOdt::NS_ODT_FO, 'margin-right', '4mm'],
            ],
        ], 'margin missings' => [
            // style
            new ezcDocumentPcssStyleMeasureBoxValue(
                ['top'    => 1, 'right'  => 4]
            ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'margin-top', '1mm'],
                [ezcDocumentOdt::NS_ODT_FO, 'margin-right', '4mm'],
            ],
        ], 'margin empty' => [
            // style
            new ezcDocumentPcssStyleMeasureBoxValue(
                ['top'    => 1, 'left'   => 0, 'bottom' => 3, 'right'  => null]
            ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'margin-top', '1mm'],
                [ezcDocumentOdt::NS_ODT_FO, 'margin-left', '0mm'],
                [ezcDocumentOdt::NS_ODT_FO, 'margin-bottom', '3mm'],
                [ezcDocumentOdt::NS_ODT_FO, 'margin-right', '0mm'],
            ],
        ]];
    }

    /**
     * @dataProvider getBorderTestSets
     */
    public function testConvertBorderProperty( $styleValue, $expectedAttributes )
    {
        $converter = new ezcDocumentOdtPcssBorderConverter();
        $converter->convert( $this->domElement, 'border', $styleValue );

        $this->assertAttributesCorrect(
            $expectedAttributes
        );
    }

    /**
     * Test sets for the 'margin' style attribute.
     */
    public static function getBorderTestSets()
    {
        return ['border full' => [
            // style
            new ezcDocumentPcssStyleBorderBoxValue(
                ['top' => ['width' => 1, 'style' => 'solid', 'color' => ['red'   => 1, 'green' => 0, 'blue'  => 0, 'alpha' => 0]], 'left' => ['width' => 10, 'style' => 'solid', 'color' => ['red'   => 0, 'green' => 1, 'blue'  => 0, 'alpha' => 0]], 'bottom' => ['width' => 1, 'style' => 'solid', 'color' => ['red'   => 0, 'green' => 0, 'blue'  => 1, 'alpha' => .8]], 'right' => ['width' => 1, 'style' => 'dotted', 'color' => ['red'   => .3, 'green' => .2, 'blue'  => .4, 'alpha' => .2]]]
            ),
            // expected attributes
            [
                // NS, attribute name, value
                [ezcDocumentOdt::NS_ODT_FO, 'border-top', '1mm solid #ff0000'],
                [ezcDocumentOdt::NS_ODT_FO, 'border-left', '10mm solid #00ff00'],
                [ezcDocumentOdt::NS_ODT_FO, 'border-bottom', '1mm solid transparent'],
                [ezcDocumentOdt::NS_ODT_FO, 'border-right', '1mm dotted #4d3366'],
            ],
        ]];
    }

}

?>
