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
class ezcDocumentPdfFooterOptionsTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentPdfFooterOptions';
    }

    public static function provideDefaultValues()
    {
        return [['height', ezcDocumentPcssMeasure::create( '15mm' )], ['footer', true], ['showDocumentTitle', true], ['showDocumentAuthor', true], ['showPageNumber', true], ['pageNumberOffset', 0], ['centerPageNumber', false]];
    }

    public static function provideValidData()
    {
        return [['footer', [true, false]], ['showDocumentTitle', [true, false]], ['showDocumentAuthor', [true, false]], ['showPageNumber', [true, false]], ['centerPageNumber', [true, false]], ['pageNumberOffset', [0, 1, 23]]];
    }

    public static function provideInvalidData()
    {
        return [['height', ['15nm', 'foo', new StdClass()]], ['footer', [1, 23, 'foo', new StdClass()]], ['showDocumentTitle', [1, 23, 'foo', new StdClass()]], ['showDocumentAuthor', [1, 23, 'foo', new StdClass()]], ['showPageNumber', [1, 23, 'foo', new StdClass()]], ['centerPageNumber', [1, 23, 'foo', new StdClass()]], ['pageNumberOffset', [true, 'foo', new StdClass()]]];
    }
}

?>
