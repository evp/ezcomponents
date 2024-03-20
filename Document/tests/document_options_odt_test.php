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
class ezcDocumentOptionsOdtTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentOdtOptions';
    }

    public static function provideDefaultValues()
    {
        $res = [['indentXml', false], ['failOnError', true], ['imageDir', sys_get_temp_dir()]];
        return $res;
    }

    public static function provideValidData()
    {
        return [['indentXml', [true, false]], ['failOnError', [true, false]], ['imageDir', [__DIR__]]];
    }

    public static function provideInvalidData()
    {
        return [['indentXml', [1, 'foo', .5, new StdClass(), []]], ['failOnError', [1, 'foo', .5, new StdClass(), []]], ['imageDir', ['/foo', 'bar', 23, true, false, []]]];
    }
}

?>
