<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @filesource
 * @package PersistentObject
 * @subpackage Tests
 */

/**
 * Tests the ezcPersistentObjectDefinition class.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentObjectDefinitionTest extends ezcTestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcPersistentObjectDefinitionTest' );
    }

    public function testConstructureSuccess()
    {
        $definition = new ezcPersistentObjectDefinition();
        $this->assertAttributeEquals(
            ['table'      => null, 'class'      => null, 'idProperty' => null, 'properties' => new ezcPersistentObjectProperties(), 'columns'    => new ezcPersistentObjectColumns(), 'relations'  => new ezcPersistentObjectRelations()],
            'propertyArray',
            $definition
        );
        
        $definition = new ezcPersistentObjectDefinition(
            'table',
            'class',
            ['foo' => new ezcPersistentObjectProperty()],
            [],
            new ezcPersistentObjectIdProperty()
        );
        
        $res = ['table'      => 'table', 'class'      => 'class', 'idProperty' => new ezcPersistentObjectIdProperty(), 'properties' => new ezcPersistentObjectProperties(), 'columns'    => new ezcPersistentObjectColumns(), 'relations'  => new ezcPersistentObjectRelations()];
        $res['properties']['foo'] = new ezcPersistentObjectProperty();

        $this->assertAttributeEquals(
            $res,
            'propertyArray',
            $definition
        );
    }

    public function testConstructureFailure()
    {
        try
        {
            $definition = new ezcPersistentObjectDefinition(
                23
            );
            $this->fail( 'ezcBaseValueException not thrown on invalid value for parameter $table.' );
        }
        catch ( ezcBaseValueException $e ) {}
        try
        {
            $definition = new ezcPersistentObjectDefinition(
                'foo',
                23
            );
            $this->fail( 'ezcBaseValueException not thrown on invalid value for parameter $class.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testGetAccessSuccess()
    {
        $definition = new ezcPersistentObjectDefinition(
            'table',
            'class'
        );

        $this->assertEquals(
            'table',
            $definition->table
        );
        $this->assertEquals(
            'class',
            $definition->class
        );
        $this->assertNull(
            $definition->idProperty
        );
        $this->assertEquals(
            new ezcPersistentObjectProperties(),
            $definition->properties
        );
    }

    public function testGetAccessFailure()
    {
        $definition = new ezcPersistentObjectDefinition(
            'table',
            'class'
        );
        try
        {
            echo $definition->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( 'Exception not thrown on get access to invalid property $foo.' );
    }
    
    public function testSetAccessSuccess()
    {
        $definition = new ezcPersistentObjectDefinition();
        $definition->class      = 'class';
        $definition->table      = 'table';
        $definition->idProperty = new ezcPersistentObjectIdProperty();
        $definition->properties = new ezcPersistentObjectProperties();
        $definition->columns    = new ezcPersistentObjectColumns();
        $definition->relations  = new ezcPersistentObjectRelations();

        $this->assertEquals(
            'class',
            $definition->class
        );
        $this->assertEquals(
            'table',
            $definition->table
        );
        $this->assertEquals(
            new ezcPersistentObjectIdProperty(),
            $definition->idProperty
        );
        $this->assertEquals(
            new ezcPersistentObjectProperties(),
            $definition->properties
        );
        $this->assertEquals(
            new ezcPersistentObjectColumns(),
            $definition->columns
        );
        $this->assertEquals(
            new ezcPersistentObjectRelations(),
            $definition->relations
        );
    }
    
    public function testSetAccessFailure()
    {
        $definition = new ezcPersistentObjectDefinition();
        $this->assertSetPropertyFails(
            $definition,
            'columnName',
            [true, false, 23, 23.42, [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $definition,
            'propertyName',
            [true, false, 23, 23.42, [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $definition,
            'propertyType',
            [true, false, 'foo', 23.42, [], new stdClass()]
        );
    }

    public function testIssetAccessSuccess()
    {
        $definition = new ezcPersistentObjectDefinition();
        $this->assertTrue(
            isset( $definition->class ),
            'Property $class seems not to be set.'
        );
        $this->assertTrue(
            isset( $definition->table ),
            'Property $table seems not to be set.'
        );
        $this->assertTrue(
            isset( $definition->properties ),
            'Property $properties seems not to be set.'
        );
        $this->assertTrue(
            isset( $definition->columns ),
            'Property $columns seems not to be set.'
        );
        $this->assertTrue(
            isset( $definition->relations ),
            'Property $relations seems not to be set.'
        );
        $this->assertFalse(
            isset( $definition->foo ),
            'Property $foo seems to be set although it does not exist.'
        );
    }

    public function testIssetAccessFailure()
    {
        $definition = new ezcPersistentObjectDefinition();
        $this->assertFalse(
            isset( $definition->foo ),
            'Property $foo seems to be set.'
        );
    }

    // http://ez.no/bugs/view/9189
    // http://ez.no/bugs/view/9187
    public function testPersistentObjectDefinitionStruct()
    {
        $property = new ezcPersistentObjectProperty(
            "test column",
            "test property",
            ezcPersistentObjectProperty::PHP_TYPE_INT
        );

        $generator = new ezcPersistentGeneratorDefinition(
            "test class",
            ["param" => 123]
        );

        $idProperty = new ezcPersistentObjectIdProperty(
            "test column",
            "test property",
            null,
            $generator
        );

        $def = new ezcPersistentObjectDefinition(
            "test table",
            "test class",
            ['test' => $property],
            [],
            $idProperty
        );

        $res = ezcPersistentObjectDefinition::__set_state(['table' => 'test table', 'class' => 'test class', 'idProperty' => 
       ezcPersistentObjectIdProperty::__set_state(['columnName' => 'test column', 'propertyName' => 'test property', 'visibility' => NULL, 'generator' => 
      ezcPersistentGeneratorDefinition::__set_state(['class' => 'test class', 'params' => 
     ['param' => 123]])]), 'properties' => 
       ['test' => 
       ezcPersistentObjectProperty::__set_state(['columnName' => 'test column', 'propertyName' => 'test property', 'propertyType' => 2])], 'columns' => 
       [], 'relations' => 
       []]);
        
        $this->assertEquals( $res, $def, "ezcPersistentObjectDefinition not deserialized correctly." );
    }

    /**
     * Test case for issue #12108.
     */
    public function testMissingReverseColumnLookup()
    {
        // Load def without def manager
        $def = require __DIR__ . '/../data/persistenttestobject.php';
        
        try
        {
            ezcPersistentStateTransformer::rowToStateArray( [], $def );
            $this->fail( 'Exception not thrown on state transformation without proper reverse-lookup.' );
        }
        catch ( ezcPersistentObjectException $e ) {}
    }
}


?>
