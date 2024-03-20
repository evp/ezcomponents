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
class ezcDocumentDocbookOptionsTests extends ezcDocumentOptionsTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function getOptionsClassName()
    {
        return 'ezcDocumentDocbookOptions';
    }

    public function testOptionsDefaultValues( $property = 'schema', $value = null )
    {
        $class = $this->getOptionsClassName();
        $option = new $class();

        $this->assertTrue(
            strpos( $option->$property, 'docbook.xsd' ) !== false
        );
    }

    public static function provideValidData()
    {
        return [['schema', [__FILE__]]];
    }

    public static function provideInvalidData()
    {
        return [['schema', ['foo', 12]]];
    }
}

?>
