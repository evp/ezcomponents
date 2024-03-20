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
class ezcConverterEzXmlDocbookOptionsTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentEzXmlToDocbookConverterOptions';
    }

    public static function provideDefaultValues()
    {
        return [['linkProvider', new ezcDocumentEzXmlDummyLinkProvider()]];
    }

    public static function provideValidData()
    {
        return [['linkProvider', [new ezcDocumentEzXmlDummyLinkProvider()]]];
    }

    public static function provideInvalidData()
    {
        return [['linkProvider', ['foo', new StdClass()]]];
    }
}

?>
