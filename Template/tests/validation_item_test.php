<?php
/**
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.2
 * @filesource
 * @package Template
 * @subpackage Tests
 */

/**
 * @package Template
 * @subpackage Tests
 */
class ezcTemplateValidationItemTest extends ezcTestCase
{
    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcTemplateValidationItemTest" );
    }

    /**
     * Test passing constructor values
     */
    public function testInit()
    {
        $validation = new ezcTemplateValidationItem( ezcTemplateValidationItem::TYPE_ERROR,
                                                     'templates/zhadum.tpl', 4, 20,
                                                     'template is not valid', 'unknown function "list_types"' );

        self::assertSame( ezcTemplateValidationItem::TYPE_ERROR, $validation->type, 'Property <type> does not return correct value.' );
        self::assertSame( 'templates/zhadum.tpl', $validation->filePath, 'Property <filePath> does not return correct value.' );
        self::assertSame( 4, $validation->line, 'Property <line> does not return correct value.' );
        self::assertSame( 20, $validation->column, 'Property <column> does not return correct value.' );
        self::assertSame( 'template is not valid', $validation->description, 'Property <description> does not return correct value.' );
        self::assertSame( 'unknown function "list_types"', $validation->details, 'Property <details> does not return correct value.' );
    }

    /**
     * Test modifying properties
     */
    public function testSetProperties()
    {
        $validation = new ezcTemplateValidationItem( ezcTemplateValidationItem::TYPE_ERROR,
                                                     'templates/zhadum.tpl', 4, 20,
                                                     'template is not valid', 'unknown function "list_types"' );

        self::assertSetProperty( $validation, 'type', [ezcTemplateValidationItem::TYPE_WARNING, ezcTemplateValidationItem::TYPE_ERROR] );
        self::assertSetProperty( $validation, 'filePath', ['templates/pagelayout.tpl', '', 'templates/zhadum.tpl'] );
        self::assertSetProperty( $validation, 'line', [0, 1, 200, 4] );
        self::assertSetProperty( $validation, 'column', [0, 1, 5, 20] );
        self::assertSetProperty( $validation, 'description', ['errors in template', 'template is not valid'] );
        self::assertSetProperty( $validation, 'details', ['invalid syntax {$list|wash}', 'unknown function "list_types"'] );
    }
}

?>
