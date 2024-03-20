<?php
/**
 * ezcDocumentPdfStyleInferenceTests
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
class ezcDocumentPcssValueParserTests extends ezcTestCase
{
    protected $document;
    protected $xpath;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public static function getMeasureBoxValues()
    {
        return [["11", ['top'    => 11., 'right'  => 11., 'bottom' => 11., 'left'   => 11.], '11.00mm 11.00mm 11.00mm 11.00mm'], ["11pt", ['top'    => 3.9, 'right'  => 3.9, 'bottom' => 3.9, 'left'   => 3.9], '3.88mm 3.88mm 3.88mm 3.88mm'], ["11 12", ['top'    => 11., 'right'  => 12., 'bottom' => 11., 'left'   => 12.], '11.00mm 12.00mm 11.00mm 12.00mm'], ["11\t \r \n \t12", ['top'    => 11., 'right'  => 12., 'bottom' => 11., 'left'   => 12.], '11.00mm 12.00mm 11.00mm 12.00mm'], ["11 12 13", ['top'    => 11., 'right'  => 12., 'bottom' => 13., 'left'   => 12.], '11.00mm 12.00mm 13.00mm 12.00mm'], ["11 12 13 14", ['top'    => 11., 'right'  => 12., 'bottom' => 13., 'left'   => 14.], '11.00mm 12.00mm 13.00mm 14.00mm'], ["11mm 12in 13px 14pt", ['top'    => 11., 'right'  => 304.8, 'bottom' => 4.6, 'left'   => 4.94], '11.00mm 304.80mm 4.59mm 4.94mm']];
    }

    /**
     * @dataProvider getMeasureBoxValues
     */
    public function testMeasureBoxValueHandler( $input, $expectation, $string )
    {
        $value = new ezcDocumentPcssStyleMeasureBoxValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid box measures read.', .1
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid measure box string serialization.'
        );
    }

    public static function getColorValues()
    {
        return [["#000000", ['red'   => 0., 'green' => 0., 'blue'  => 0., 'alpha' => 0.], '#000000'], ["#ffffff", ['red'   => 1., 'green' => 1., 'blue'  => 1., 'alpha' => 0.], '#ffffff'], ["#babdb6", ['red'   => .73, 'green' => .74, 'blue'  => .71, 'alpha' => 0.], '#babdb6'], ["#babdb6b0", ['red'   => .73, 'green' => .74, 'blue'  => .71, 'alpha' => .69], '#babdb6b0'], ["#BABDB6", ['red'   => .73, 'green' => .74, 'blue'  => .71, 'alpha' => 0.], '#babdb6'], ["#000", ['red'   => 0., 'green' => 0., 'blue'  => 0., 'alpha' => 0.], '#000000'], ["#fff", ['red'   => 1., 'green' => 1., 'blue'  => 1., 'alpha' => 0.], '#ffffff'], ["#bad", ['red'   => .73, 'green' => .67, 'blue'  => .87, 'alpha' => 0.], '#bbaadd'], ["#bad6", ['red'   => .73, 'green' => .67, 'blue'  => .87, 'alpha' => .4], '#bbaadd66'], ["#BAD", ['red'   => .73, 'green' => .67, 'blue'  => .87, 'alpha' => 0.], '#bbaadd'], ["rgb( 0, 255, 9823 )", ['red'   => .0, 'green' => 1., 'blue'  => .37, 'alpha' => 0.], '#00ff5f'], ["   RGB     ( 0 , 10 , 127 ) ", ['red'   => .0, 'green' => .04, 'blue'  => .5, 'alpha' => 0.], '#000a7f'], ["rgba( 0, 255, 1023, 127 )", ['red'   => .0, 'green' => 1., 'blue'  => 1., 'alpha' => .5], '#00ffff7f'], ["   RGBA     ( 12 , 23 , 1023 , 12 ) ", ['red'   => .05, 'green' => .1, 'blue'  => 1., 'alpha' => .05], '#0c17ff0c'], ["transparent", ['red'   => 0., 'green' => 0., 'blue'  => 0., 'alpha' => 1.], '#000000ff'], ["none", ['red'   => 0., 'green' => 0., 'blue'  => 0., 'alpha' => 1.], '#000000ff']];
    }

    /**
     * @dataProvider getColorValues
     */
    public function testColorValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleColorValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid color values read.', .01
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid color string serialization.'
        );
    }

    public function testInvalidColorSpecification()
    {
        try {
            $value = new ezcDocumentPcssStyleColorValue();
            $value->parse( 'something invalid' );
            $this->fail( 'Expected ezcDocumentParserException.' );
        } catch ( ezcDocumentParserException $e )
        { /* Expected */ }
    }

    public static function getLineStyleValues()
    {
        return [["solid", "solid", 'solid'], [" dotted ", 'dotted', 'dotted'], ["\t\ngroove\r", 'groove', 'groove']];
    }

    /**
     * @dataProvider getLineStyleValues
     */
    public function testLineValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleLineValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid style style value read.', .01
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid style style string serialization.'
        );
    }

    public static function getBorderStyleValues()
    {
        return [["1mm", ['width' => 1, 'style'  => 'solid', 'color' => ['red'   => 1., 'green' => 1., 'blue'  => 1., 'alpha' => 0.]], '1.00mm solid #ffffff'], ["dashed", ['width' => 0, 'style'  => 'dashed', 'color' => ['red'   => 1., 'green' => 1., 'blue'  => 1., 'alpha' => 0.]], '0.00mm dashed #ffffff'], ["rgb( 255, 0, 0 )", ['width' => 0, 'style'  => 'solid', 'color' => ['red'   => 1., 'green' => 0., 'blue'  => 0., 'alpha' => 0.]], '0.00mm solid #ff0000'], ["1pt #F00", ['width' => .35, 'style'  => 'solid', 'color' => ['red'   => 1., 'green' => 0., 'blue'  => 0., 'alpha' => 0.]], '0.35mm solid #ff0000'], ["1 inset #0f0", ['width' => 1., 'style'  => 'inset', 'color' => ['red'   => 0., 'green' => 1., 'blue'  => 0., 'alpha' => 0.]], '1.00mm inset #00ff00']];
    }

    /**
     * @dataProvider getBorderStyleValues
     */
    public function testBorderValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleBorderValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid border style value read.', .01
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid border style string serialization.'
        );
    }

    public static function getColorBoxStyleValues()
    {
        return [["#0f0 #ff0000 rgb( 0, 0, 255 )", ['top' => ['red'   => 0., 'green' => 1., 'blue'  => 0., 'alpha' => 0.], 'right' => ['red'   => 1., 'green' => 0., 'blue'  => 0., 'alpha' => 0.], 'bottom' => ['red'   => 0., 'green' => 0., 'blue'  => 1., 'alpha' => 0.], 'left' => ['red'   => 1., 'green' => 0., 'blue'  => 0., 'alpha' => 0.]], '#00ff00 #ff0000 #0000ff #ff0000'], ["#fF0", ['top' => ['red'   => 1., 'green' => 1., 'blue'  => 0., 'alpha' => 0.], 'right' => ['red'   => 1., 'green' => 1., 'blue'  => 0., 'alpha' => 0.], 'bottom' => ['red'   => 1., 'green' => 1., 'blue'  => 0., 'alpha' => 0.], 'left' => ['red'   => 1., 'green' => 1., 'blue'  => 0., 'alpha' => 0.]], '#ffff00 #ffff00 #ffff00 #ffff00']];
    }

    /**
     * @dataProvider getColorBoxStyleValues
     */
    public function testColorBoxValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleColorBoxValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid color value read.', .01
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid color box string serialization.'
        );
    }

    public static function getLineBoxStyleValues()
    {
        return [["solid double outset", ['top'    => 'solid', 'right'  => 'double', 'bottom' => 'outset', 'left'   => 'double'], 'solid double outset double'], ["inset", ['top'    => 'inset', 'right'  => 'inset', 'bottom' => 'inset', 'left'   => 'inset'], 'inset inset inset inset']];
    }

    /**
     * @dataProvider getLineBoxStyleValues
     */
    public function testLineBoxValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleLineBoxValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid style value read.', .01
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid style box string serialization.'
        );
    }

    public static function getBorderBoxStyleValues()
    {
        return [["1 inset #0f0", ['top' => ['width' => 1., 'style'  => 'inset', 'color' => ['red'   => 0., 'green' => 1., 'blue'  => 0., 'alpha' => 0.]], 'right' => ['width' => 1., 'style'  => 'inset', 'color' => ['red'   => 0., 'green' => 1., 'blue'  => 0., 'alpha' => 0.]], 'bottom' => ['width' => 1., 'style'  => 'inset', 'color' => ['red'   => 0., 'green' => 1., 'blue'  => 0., 'alpha' => 0.]], 'left' => ['width' => 1., 'style'  => 'inset', 'color' => ['red'   => 0., 'green' => 1., 'blue'  => 0., 'alpha' => 0.]]], '1.00mm inset #00ff00 1.00mm inset #00ff00 1.00mm inset #00ff00 1.00mm inset #00ff00'], ["1mm #fF0 outset 2mm", ['top' => ['width' => 1., 'style'  => 'solid', 'color' => ['red'   => 1., 'green' => 1., 'blue'  => 0., 'alpha' => 0.]], 'right' => ['width' => 0., 'style'  => 'outset', 'color' => ['red'   => 1., 'green' => 1., 'blue'  => 1., 'alpha' => 0.]], 'bottom' => ['width' => 2., 'style'  => 'solid', 'color' => ['red'   => 1., 'green' => 1., 'blue'  => 1., 'alpha' => 0.]], 'left' => ['width' => 0., 'style'  => 'outset', 'color' => ['red'   => 1., 'green' => 1., 'blue'  => 1., 'alpha' => 0.]]], '1.00mm solid #ffff00 0.00mm outset #ffffff 2.00mm solid #ffffff 0.00mm outset #ffffff']];
    }

    /**
     * @dataProvider getBorderBoxStyleValues
     */
    public function testBorderBoxValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleBorderBoxValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid border style value read.', .01
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid border style string serialization.'
        );
    }

    public static function getUrlStyleValues()
    {
        return [["url( foo.ttf )", ["foo.ttf"], "url( foo.ttf )"], ["url(foo.ttf),local(font.pfb),url(/some/../path/to/font.foo)", ["foo.ttf", "font.pfb", "/some/../path/to/font.foo"], "url( foo.ttf ), url( font.pfb ), url( /some/../path/to/font.foo )"]];
    }

    /**
     * @dataProvider getUrlStyleValues
     */
    public function testSrcValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleSrcValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Invalid src style value read.', .01
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid src style string serialization.'
        );
    }

    public static function getListStyleValues()
    {
        return [['single', ['single'], 'single'], ['single     ', ['single'], 'single'], ['first second', ['first', 'second'], 'first second'], ['first     second   ', ['first', 'second'], 'first second'], ['    first              second    ', ['first', 'second'], 'first second'], ['    first              second                  third', ['first', 'second', 'third'], 'first second third']];
    }

    /**
     * @dataProvider getListStyleValues
     */
    public function testListValueHandler( $input, $expectation, $string = '' )
    {
        $value = new ezcDocumentPcssStyleListValue();
        $value->parse( $input );

        $this->assertEquals(
            $expectation,
            $value->value,
            'Incorrect list value read.'
        );

        $this->assertEquals(
            $string,
            (string) $value,
            'Invalid list value string serialization.'
        );
    }
}

?>
