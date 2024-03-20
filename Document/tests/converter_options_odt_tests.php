<?php
/**
 * ezcDocTestConvertXhtmlDocbook
 * 
 * @package Document
 * @version 1.3.1
 * @subpackage Tests
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

require_once __DIR__ . '/options_test_case.php';

/**
 * Test suite for class.
 * 
 * @package Document
 * @subpackage Tests
 */
class ezcConverterOdtOptionsTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentDocbookToOdtConverterOptions';
    }

    public static function provideDefaultValues()
    {
        return [['styler', new ezcDocumentOdtPcssStyler()], ['lengthMeasure', 'px']];
    }

    public static function provideValidData()
    {
        return [['template', [__FILE__]], ['styler', [new ezcDocumentOdtPcssStyler()]], ['lengthMeasure', ['cm', 'mm', 'in', 'pt', 'pc', 'px']]];
    }

    public static function provideInvalidData()
    {
        return [['template', ['foo', '23', new StdClass()]], ['styler', ['foo', 23, new StdClass()]], ['lengthMeasure', ['foo', 23, new StdClass()]]];
    }
}

?>
