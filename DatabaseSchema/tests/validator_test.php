<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.4.4
 * @filesource
 * @package DatabaseSchema
 * @subpackage Tests
 */

/**
 * @package DatabaseSchema
 * @subpackage Tests
 */
class ezcDatabaseSchemaValidatorTest extends ezcTestCase
{
    protected function setUp()
    {
        try
        {
            $this->db = ezcDbInstance::get();
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped();
        }

    }

    public function testIndexFields()
    {
        $schema = new ezcDbSchema(
            ['bugdb' => new ezcDbSchemaTable(
                ['field1' => new ezcDbSchemaField( 'integer' ), 'field2' => new ezcDbSchemaField( 'integer' )],
                ['index1' => new ezcDbSchemaIndex( ['field1' => new ezcDbSchemaIndexField()] ), 'index2' => new ezcDbSchemaIndex( ['field3' => new ezcDbSchemaIndexField()] ), 'index3' => new ezcDbSchemaIndex( ['field2' => new ezcDbSchemaIndexField(), 'field3' => new ezcDbSchemaIndexField()] )]
            )]
        );

        $expected = ["Index 'bugdb:index2' references unknown field name 'bugdb:field3'.", "Index 'bugdb:index3' references unknown field name 'bugdb:field3'."];
        self::assertEquals( $expected, ezcDbSchemaValidator::validate( $schema ) );
    }

    public function testTypes()
    {
        $schema = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'int' ), 'booleanfield1' => new ezcDbSchemaField( 'boolean' ), 'booleanfield2' => new ezcDbSchemaField( 'bool' ), 'floatfield1' => new ezcDbSchemaField( 'float' ), 'floatfield2' => new ezcDbSchemaField( 'double' ), 'decimalfield1' => new ezcDbSchemaField( 'decimal' ), 'decimalfield2' => new ezcDbSchemaField( 'numeric' ), 'timestampfield1' => new ezcDbSchemaField( 'timestamp' ), 'timefield1' => new ezcDbSchemaField( 'time' ), 'datefield1' => new ezcDbSchemaField( 'date' ), 'textfield1' => new ezcDbSchemaField( 'text' ), 'textfield2' => new ezcDbSchemaField( 'char' ), 'textfield3' => new ezcDbSchemaField( 'varchar' ), 'blobfield1' => new ezcDbSchemaField( 'blob' ), 'clobfield1' => new ezcDbSchemaField( 'clob' )]
        )] );

        $expected = ["Field 'bugdb:integerfield2' uses the unsupported type 'int'.", "Field 'bugdb:booleanfield2' uses the unsupported type 'bool'.", "Field 'bugdb:floatfield2' uses the unsupported type 'double'.", "Field 'bugdb:decimalfield2' uses the unsupported type 'numeric'.", "Field 'bugdb:textfield2' uses the unsupported type 'char'.", "Field 'bugdb:textfield3' uses the unsupported type 'varchar'."];
        self::assertEquals( $expected, ezcDbSchemaValidator::validate( $schema ) );
    }

    public function testMissingIndexForAutoincrementField()
    {
        $schema = new ezcDbSchema(
            ['bugdb' => new ezcDbSchemaTable(
                ['id' => new ezcDbSchemaField( 'integer', false, true, null, true )]
            ), 'bugdb2' => new ezcDbSchemaTable(
                ['id' => new ezcDbSchemaField( 'integer', false, true, null, true )],
                ['primary' => new ezcDbSchemaIndex( ['id' => new ezcDbSchemaIndexField()], true )]
            )]
        );
        $expected = ["Field 'bugdb:id' is auto increment but there is no primary index defined."];
        self::assertEquals( $expected, ezcDbSchemaValidator::validate( $schema ) );
    }

    public function testForDuplicateIndexName1()
    {
        $schema = new ezcDbSchema(
            ['table1' => new ezcDbSchemaTable(
                ['field1' => new ezcDbSchemaField( 'integer' )],
                ['index1' => new ezcDbSchemaIndex( ['field1' => new ezcDbSchemaIndexField()] )]
            ), 'table2' => new ezcDbSchemaTable(
                ['field1' => new ezcDbSchemaField( 'integer' )],
                ['index1' => new ezcDbSchemaIndex( ['field1' => new ezcDbSchemaIndexField()] )]
            )]
        );

        $expected = ["The index name 'index1' is not unique. It exists for the tables: 'table1', 'table2'."];
        self::assertEquals( $expected, ezcDbSchemaValidator::validate( $schema ) );
    }

    public function testForDuplicateIndexName2()
    {
        $schema = new ezcDbSchema(
            ['table1' => new ezcDbSchemaTable(
                ['field1' => new ezcDbSchemaField( 'integer' )],
                ['index1' => new ezcDbSchemaIndex( ['field1' => new ezcDbSchemaIndexField()] )]
            ), 'table2' => new ezcDbSchemaTable(
                ['field1' => new ezcDbSchemaField( 'integer' )],
                ['index2' => new ezcDbSchemaIndex( ['field1' => new ezcDbSchemaIndexField()] )]
            ), 'table3' => new ezcDbSchemaTable(
                ['field1' => new ezcDbSchemaField( 'integer' )],
                ['index1' => new ezcDbSchemaIndex( ['field1' => new ezcDbSchemaIndexField()] )]
            ), 'table4' => new ezcDbSchemaTable(
                ['field1' => new ezcDbSchemaField( 'integer' )],
                ['index1' => new ezcDbSchemaIndex( ['field1' => new ezcDbSchemaIndexField()] )]
            )]
        );

        $expected = ["The index name 'index1' is not unique. It exists for the tables: 'table1', 'table3', 'table4'."];
        self::assertEquals( $expected, ezcDbSchemaValidator::validate( $schema ) );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcDatabaseSchemaValidatorTest' );
    }
}
?>
