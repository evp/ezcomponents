<?php
/**
 * ezcDocumentPdfHyphenatorTests
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
class ezcDocumentPdfHyphenatorTests extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testDefaultHyphenator()
    {
        $hyphenator = new ezcDocumentPdfDefaultHyphenator();
        $this->assertSame(
            [['foo']],
            $hyphenator->splitWord( 'foo' )
        );
    }
}
?>
