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
class ezcDatabaseSchemaComparatorTest extends ezcTestCase
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

    public function testCompareSame1()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )]
        )] );
        self::assertEquals( new ezcDbSchemaDiff(), ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareSame2()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield2' => new ezcDbSchemaField( 'integer' ), 'integerfield1' => new ezcDbSchemaField( 'integer' )]
        )] );
        self::assertEquals( new ezcDbSchemaDiff(), ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testComparePrimaryUniqueAndNonUniqueMakesNoDifference()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )],
            ['primary' => new ezcDbSchemaIndex(
                ['integerfield1' => new ezcDbSchemaIndexField()],
                true, true
            )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )],
            ['primary' => new ezcDbSchemaIndex(
                ['integerfield1' => new ezcDbSchemaIndexField()],
                true, false
            )]
        )] );

        $diff = ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 );
        $this->assertEquals(0, count($diff->changedTables));
    }

    public function testCompareMissingTable()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )]
        )] );
        $schema2 = new ezcDbSchema( [] );

        $expected = new ezcDbSchemaDiff( [], [],
            ['bugdb' => true]
        );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareNewTable()
    {
        $schema1 = new ezcDbSchema( [] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )]
        )] );

        $expected = new ezcDbSchemaDiff( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )]
        )] );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareMissingField()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield2' => new ezcDbSchemaField( 'integer' )]
        )] );

        $expected = new ezcDbSchemaDiff ( [], 
            ['bugdb' => new ezcDbSchemaTableDiff( [], [],
                ['integerfield1' => true]
            )]
        );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareNewField()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )]
        )] );

        $expected = new ezcDbSchemaDiff ( [], 
            ['bugdb' => new ezcDbSchemaTableDiff (
                ['integerfield2' => new ezcDbSchemaField( 'integer' )]
            )]
        );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareChangedFields()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['charfield1' => new ezcDbSchemaField( 'char', 32, true, "default", false )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['charfield1' => new ezcDbSchemaField( 'char', 32, true, "default", true )]
        )] );

        $expected = new ezcDbSchemaDiff ( [], 
            ['bugdb' => new ezcDbSchemaTableDiff( [],
                ['charfield1' => new ezcDbSchemaField( 'char', 32, true, "default", true )]
            )]
        );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareRemovedIndex()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )],
            ['primary' => new ezcDbSchemaIndex(
                ['integerfield1' => new ezcDbSchemaIndexField()],
                true
            )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )]
        )] );

        $expected = new ezcDbSchemaDiff ( [], 
            ['bugdb' => new ezcDbSchemaTableDiff( [], [], [], [], [],
                ['primary' => true]
            )]
        );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareNewIndex()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )],
            ['primary' => new ezcDbSchemaIndex(
                ['integerfield1' => new ezcDbSchemaIndexField()],
                true
            )]
        )] );

        $expected = new ezcDbSchemaDiff ( [], 
            ['bugdb' => new ezcDbSchemaTableDiff( [], [], [],
                ['primary' => new ezcDbSchemaIndex(
                    ['integerfield1' => new ezcDbSchemaIndexField()],
                    true
                )]
            )]
        );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public function testCompareChangedIndex()
    {
        $schema1 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )],
            ['primary' => new ezcDbSchemaIndex(
                ['integerfield1' => new ezcDbSchemaIndexField()],
                true
            )]
        )] );
        $schema2 = new ezcDbSchema( ['bugdb' => new ezcDbSchemaTable(
            ['integerfield1' => new ezcDbSchemaField( 'integer' ), 'integerfield2' => new ezcDbSchemaField( 'integer' )],
            ['primary' => new ezcDbSchemaIndex(
                ['integerfield1' => new ezcDbSchemaIndexField(), 'integerfield2' => new ezcDbSchemaIndexField()],
                true
            )]
        )] );

        $expected = new ezcDbSchemaDiff ( [], 
            ['bugdb' => new ezcDbSchemaTableDiff( [], [], [], [],
                ['primary' => new ezcDbSchemaIndex(
                    ['integerfield1' => new ezcDbSchemaIndexField(), 'integerfield2' => new ezcDbSchemaIndexField()],
                    true
                )]
            )]
        );
        self::assertEquals( $expected, ezcDbSchemaComparator::compareSchemas( $schema1, $schema2 ) );
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcDatabaseSchemaComparatorTest' );
    }
}
?>
