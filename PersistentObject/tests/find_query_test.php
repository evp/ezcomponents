<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @package PersistentObject
 * @subpackage Tests
 */

/**
 * Tests the ezcPersistentFindQuery class.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentFindQueryTest extends ezcTestCase
{
    protected $db;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function setUp()
    {
        try
        {
            $this->db = ezcDbFactory::create( 'sqlite://:memory:' );
        }
        catch ( Exception $e )
        {
            $this->markTestSkipped( 'Missing SQLite support to use query abstraction.' );
        }
    }

    public function tearDown()
    {
        unset( $this->db );
    }

    public function testCtor()
    {
        $q = new ezcQuerySelect( $this->db );
        $cn = 'myCustomClassName';

        $findQuery = new ezcPersistentFindQuery( $q, $cn );

        $this->assertAttributeEquals(
            ['className' => $cn, 'query'     => $q],
            'properties',
            $findQuery
        );
    }

    public function testSetOwnPropertiesFailure()
    {
        $findQuery = $this->createFindQuery();

        try
        {
            $findQuery->query = 'some thing';
            $this->fail( 'Exception not thrown on set access to property $query.' );
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            $this->assertEquals(
                "The property 'query' is read-only.",
                $e->getMessage()
            );
        }

        try
        {
            $findQuery->className = 'some thing';
            $this->fail( 'Exception not thrown on set access to property $className.' );
        }
        catch ( ezcBasePropertyPermissionException $e )
        {
            $this->assertEquals(
                "The property 'className' is read-only.",
                $e->getMessage()
            );
        }

        try
        {
            $findQuery->metaData = 'foo';
            $this->fail( 'Exception not thrown on set access to property $metaData.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}

        try
        {
            $findQuery->metaData = new ArrayObject();
            $this->fail( 'Exception not thrown on set access to property $metaData.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testSetQueryPropertiesFailure()
    {
        $findQuery = $this->createFindQuery();

        try
        {
            $findQuery->someProperty = 'foo';
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testSetQueryPropertiesSuccess()
    {
        $q = new ezcQuerySelect( $this->db );
        $cn = 'myCustomClassName';
        $findQuery = new ezcPersistentFindQuery( $q, $cn );

        $expr = new ezcQueryExpression( $this->db );

        $findQuery->expr = $expr;

        $this->assertSame(
            $expr,
            $q->expr
        );
    }

    public function testGetOwnPropertiesSuccess()
    {
        $q = new ezcQuerySelect( $this->db );
        $cn = 'myCustomClassName';
        $findQuery = new ezcPersistentFindQuery( $q, $cn );
        
        $this->assertEquals(
            'myCustomClassName',
            $findQuery->className
        );
        $this->assertSame(
            $q,
            $findQuery->query
        );
    }

    public function testGetQueryPropertiesSuccess()
    {
        $q = new ezcQuerySelect( $this->db );
        $cn = 'myCustomClassName';
        $findQuery = new ezcPersistentFindQuery( $q, $cn );
        
        $this->assertEquals(
            new ezcQueryExpressionSqlite( $this->db ),
            $findQuery->query->expr
        );
    }

    public function testGetPropertiesFailure()
    {
        $findQuery = $this->createFindQuery();

        try
        {
            echo $findQuery->fooBar;
            $this->fail( 'Exception not thrown on get access to non-existent property $fooBar.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testIssetOwnPropertiesSuccess()
    {
        $findQuery = $this->createFindQuery();

        $this->assertTrue(
            isset( $findQuery->query )
        );
        $this->assertTrue(
            isset( $findQuery->className )
        );
    }

    public function testIssetQueryPropertiesSuccess()
    {
        $findQuery = $this->createFindQuery();

        $this->assertTrue(
            isset( $findQuery->expr )
        );
    }

    public function testIssetPropertiesFailure()
    {
        $findQuery = $this->createFindQuery();

        $this->assertFalse(
            isset( $findQuery->fooBar )
        );
    }

    public function testDelegateSuccess()
    {
        $q = $this->getMock(
            'ezcQuerySelect',
            ['reset', 'alias', 'select'],
            [],
            '',
            false,
            false
        );
        $q->expects( $this->once() )
           ->method( 'reset' )
           ->will( $this->returnValue( 23 ) );
        $q->expects( $this->once() )
           ->method( 'alias' )
           ->with( 'someName', 'someAlias' );
        $q->expects( $this->never() )->method( 'select' );

        $cn = 'myCustomClassName';
        
        $findQuery = new ezcPersistentFindQuery( $q, $cn );
        
        $this->assertEquals(
            23,
            $findQuery->reset()
        );
        
        $this->assertNull(
            $findQuery->alias( 'someName', 'someAlias' )
        );
    }

    public function testFluentInterface()
    {
        $q = $this->createFindQuery();

        $q2 = $q->select( 'foo', 'bar' );

        $this->assertSame( $q, $q2 );

        $q3 = $q2->from( 'baz' );

        $this->assertSame( $q, $q3 );
    }

    protected function createFindQuery()
    {
        $q = new ezcQuerySelect( $this->db );
        $cn = 'myCustomClassName';

        return new ezcPersistentFindQuery( $q, $cn );
    }
}

?>
