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
class ezcConverterRstOptionsTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentDocbookToRstConverterOptions';
    }

    public static function provideDefaultValues()
    {
        return [['headerTypes', ['==', '--', '=', '-', '^', '~', '`', '*', ':', '+', '/', '.']], ['wordWrap', 78], ['itemListCharacter', '-']];
    }

    public static function provideValidData()
    {
        return [['headerTypes', [['--'], ['--', '=', '"']]], ['wordWrap', [20, 1023]], ['itemListCharacter', ['*', "\xe2\x80\xa2"]]];
    }

    public static function provideInvalidData()
    {
        return [['headerTypes', ['--', 23]], ['wordWrap', ['foo', new StdClass()]], ['itemListCharacter', ['>', 23]]];
    }
}

?>
