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
class ezcConverterXsltOptionsTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentXsltConverterOptions';
    }

    public static function provideDefaultValues()
    {
        return [['xslt', null], ['parameters', []], ['failOnError', false]];
    }

    public static function provideValidData()
    {
        return [['xslt', ['http://example.org/']], ['parameters', [['foo' => 'bar']]], ['failOnError', [true]]];
    }

    public static function provideInvalidData()
    {
        return [['parameters', ['foo', new StdClass()]], ['failOnError', ['foo', 23]]];
    }
}

?>
