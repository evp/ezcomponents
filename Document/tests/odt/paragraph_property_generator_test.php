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

require_once 'property_generator_test.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcDocumentOdtStyleParagraphPropertyGeneratorTest extends ezcDocumentOdtStylePropertyGeneratorTest
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testCtor()
    {
        $gen = new ezcDocumentOdtStyleParagraphPropertyGenerator(
            $this->styleConverters
        );

        $this->assertAttributeSame(
            $this->styleConverters,
            'styleConverters',
            $gen
        );

        $this->assertAttributeEquals(
            ['text-align', 'widows', 'orphans', 'text-indent', 'margin', 'border', 'break-before'],
            'styleAttributes',
            $gen
        );
    }

    public function testCreateProperty()
    {
        $gen = new ezcDocumentOdtStyleParagraphPropertyGenerator(
            $this->styleConverters
        );
        $parent = $this->getDomElementFixture();

        $gen->createProperty(
            $parent,
            ['text-align' => new ezcDocumentPcssStyleStringValue( 'center' )]
        );

        $this->assertPropertyExists(
            ezcDocumentOdt::NS_ODT_STYLE,
            'paragraph-properties',
            [[ezcDocumentOdt::NS_ODT_FO, 'text-align']],
            $parent
        );
    }
}

?>
