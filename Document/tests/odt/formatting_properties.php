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
class ezcDocumentOdtFormattingPropertiesTest extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testConstructorSuccess()
    {
        $props = new ezcDocumentOdtFormattingProperties(
            ezcDocumentOdtFormattingProperties::PROPERTIES_TEXT
        );

        $this->assertAttributeEquals(
            ['type' => ezcDocumentOdtFormattingProperties::PROPERTIES_TEXT],
            'properties',
            $props
        );
    }
    
    public function testAppendValueFailure()
    {
        $props = new ezcDocumentOdtFormattingProperties(
            ezcDocumentOdtFormattingProperties::PROPERTIES_TEXT
        );

        try
        {
            $props->append( 'foo' );
            $this->fail( 'Exception not thrown on invalid method call to append().' );
        }
        catch ( RuntimeException $e ) {}
    }
    
    public function testExchangeArrayFailure()
    {
        $props = new ezcDocumentOdtFormattingProperties(
            ezcDocumentOdtFormattingProperties::PROPERTIES_TEXT
        );

        try
        {
            $props->exchangeArray( [] );
            $this->fail( 'Exception not thrown on invalid method call to exchangeArray().' );
        }
        catch ( RuntimeException $e ) {}
    }

    public function testOffsetSetSuccess()
    {
        $props = new ezcDocumentOdtFormattingProperties(
            ezcDocumentOdtFormattingProperties::PROPERTIES_TEXT
        );

        $props['foo'] = 23;

        $this->assertEquals(
            23,
            $props['foo']
        );
    }

    public function testOffsetSetFailure()
    {
        $props = new ezcDocumentOdtFormattingProperties(
            ezcDocumentOdtFormattingProperties::PROPERTIES_TEXT
        );

        try
        {
            $props[23] = 'foo';
            $this->fail( 'Exception not thrown on invalid offset 23.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }
}

?>
