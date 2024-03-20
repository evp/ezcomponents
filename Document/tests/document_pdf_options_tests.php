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
class ezcDocumentPdfOptionsTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentPdfOptions';
    }

    public static function provideDefaultValues()
    {
        return [['errorReporting', 15], ['hyphenator', new ezcDocumentPdfDefaultHyphenator()], ['tokenizer', new ezcDocumentPdfDefaultTokenizer()], ['tableColumnWidthCalculator', new ezcDocumentPdfDefaultTableColumnWidthCalculator()], ['driver', null], ['compress', false], ['ownerPassword', null], ['userPassword', null], ['permissions', -1]];
    }

    public static function provideValidData()
    {
        return [['errorReporting', [E_PARSE, E_PARSE | E_NOTICE]], ['hyphenator', [new ezcDocumentPdfDefaultHyphenator()]], ['tokenizer', [new ezcDocumentPdfDefaultTokenizer()]], ['tableColumnWidthCalculator', [new ezcDocumentPdfDefaultTableColumnWidthCalculator()]], ['driver', [new ezcDocumentPdfHaruDriver()]], ['compress', [true, false]], ['ownerPassword', ['foo', null]], ['userPassword', [null]], ['permissions', [0, -1, ezcDocumentPdfOptions::EDIT | ezcDocumentPdfOptions::PRINTABLE]]];
    }

    public static function provideInvalidData()
    {
        return [['errorReporting', ['foo', E_ALL & ~E_PARSE]], ['hyphenator', ['foo', new StdClass()]], ['tokenizer', ['foo', new StdClass()]], ['tableColumnWidthCalculator', ['foo', new StdClass()]], ['driver', ['foo', new StdClass()]], ['compress', [1, null, 23.4, 'foo', new StdClass()]], ['ownerPassword', [1, 23.4, new StdClass()]], ['userPassword', ['foo', 1, 23.4, new StdClass()]], ['permissions', [null, 23.4, 'foo', new StdClass()]]];
    }
}

?>
