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
 * Tests the ezcPersistentObjecRelations class.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentObjectRelationsTest extends ezcTestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( 'ezcPersistentObjectRelationsTest' );
    }

    public function testConstructureSuccess()
    {
        $relations = new ezcPersistentObjectRelations();
        $this->assertEquals(
            0,
            count( $relations )
        );
    }

    public function testArrayAccessSuccess()
    {
        $relations = new ezcPersistentObjectRelations();
        $relation = new ezcPersistentOneToManyRelation( 'foo', 'bar' );
        $relations['foo'] = $relation;

        $this->assertEquals(
            1,
            count( $relations )
        );
        $this->assertSame(
            $relation,
            $relations['foo']
        );
    }

    public function testArrayAccessFailure()
    {
        $relations = new ezcPersistentObjectRelations();

        $this->genericArrayAccessFailure(
            $relations,
            'foo',
            [23, 23.42, true, "test", [], new stdClass()],
            'ezcBaseValueException'
        );
        $this->genericArrayAccessFailure(
            $relations,
            23,
            [new ezcPersistentOneToManyRelation( 'foo', 'bar' )],
            'ezcBaseValueException'
        );
    }

    public function testExchangeArraySuccess()
    {
        $relations = new ezcPersistentObjectRelations();
        $relations['foo'] = new ezcPersistentOneToManyRelation( 'foo', 'bar' );
        
        $this->assertEquals(
            1,
            count( $relations )
        );

        $relations->exchangeArray( [] );
        $this->assertEquals(
            0,
            count( $relations )
        );

        $relations->exchangeArray(
            ['foo' => new ezcPersistentOneToManyRelation( 'foo', 'bar' ), 'bar' => new ezcPersistentOneToManyRelation( 'foo', 'bar' )]
        );
        $this->assertEquals(
            2,
            count( $relations )
        );
    }

    public function testExchangeArrayFailure()
    {
        $relations = new ezcPersistentObjectRelations();
        $relations['foo'] = new ezcPersistentOneToManyRelation( 'foo', 'bar' );
        
        $this->assertEquals(
            1,
            count( $relations )
        );

        try
        {
            $relations->exchangeArray(
                ['foo' => 23]
            );
            $this->fail( 'ezcBaseValueException not thrown on invalid value in exchange array.' );
        }
        catch ( ezcBaseValueException $e ) {}

        try
        {
            $relations->exchangeArray(
                [23 => new ezcPersistentObjectProperty]
            );
            $this->fail( 'ezcBaseValueException not thrown on invalid offset in exchange array.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testSetFlagsSuccess()
    {
        $relations = new ezcPersistentObjectRelations();
        $relations->setFlags( 0 );
        $this->assertEquals(
            0,
            $relations->getFlags()
        );
    }

    public function testSetFlagsFailure()
    {
        $relations = new ezcPersistentObjectRelations();

        try
        {
            $relations->setFlags( 23 );
            $this->fail( 'ezcBaseValueException not thrown on flags different to 0.' );
        }
        catch ( ezcBaseValueException $e ) {}
        $this->assertEquals(
            0,
            $relations->getFlags()
        );
    }

    public function testAppendFailure()
    {
        $relations = new ezcPersistentObjectRelations();

        try
        {
            $relations[] = new ezcPersistentOneToManyRelation( 'foo', 'bar' );
            $this->fail( 'ezcBaseValueException not thrown on trying to append.' );
        }
        catch ( Exception $e ) {}
        $this->assertEquals(
            0,
            count( $relations )
        );
    }

    protected function genericArrayAccessFailure( ArrayAccess $relations, $offset, array $values, $exceptionClass )
    {
        foreach ( $values as $value )
        {
            try
            {
                $relations[$offset] = $value;
                $this->fail( $exceptionClass . ' not thrown on value ' . gettype( $value ) . ' for offset ' . $offset . ' in class ' . get_class( $relations ) . '.' );
            }
            catch ( Exception $e )
            {
                $this->assertEquals(
                    $exceptionClass,
                    get_class( $e ),
                    $exceptionClass . ' not thrown on value ' . gettype( $value ) . ' for offset ' . $offset . ' in class ' . get_class( $relations ) . ', ' . get_class( $e ) . ' thrown instead.'
                );
            }
        }
    }

}


?>
