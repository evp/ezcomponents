<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @package PersistentObject
 * @subpackage Tests
 */

require_once 'data/relation_test_person.php';
require_once 'data/relation_test_address.php';
require_once 'data/relation_test_employer.php';
require_once 'data/multi_relation_test_person.php';

/**
 * Tests the ezcPersistentBasicIdentityMap class.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentBasicIdentityMapTest extends ezcTestCase
{
    protected $definitionManager;

    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function setUp()
    {
        $this->definitionManager = new ezcPersistentCodeManager(
            __DIR__ . '/data'
        );
    }

    public function tearDown()
    {
    }

    /*
     * __construct()
     */

    public function testCtor()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );

        $this->assertAttributeSame(
            $this->definitionManager,
            'definitionManager',
            $idMap
        );
        $this->assertAttributeEquals(
            [],
            'identities',
            $idMap
        );
    }

    /* 
     * getIdentity()
     */

    public function testGetIdentityFailureNotExists()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $this->assertNull(
            $idMap->getIdentity( 'RelationTestPerson', 23 )
        );
    }

    public function testGetIdentitySingleRecordedSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );
        
        $this->assertSame(
            $obj,
            $idMap->getIdentity( 'RelationTestPerson', 23 )
        );
    }

    public function testGetIdentityMultipleRecordedSameClassSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $objA     = new RelationTestPerson();
        $objA->id = 23;
        
        $objB     = new RelationTestPerson();
        $objB->id = 42;

        $idMap->setIdentity( $objA );
        $idMap->setIdentity( $objB );

        $this->assertSame(
            $objA,
            $idMap->getIdentity( 'RelationTestPerson', 23 )
        );

        $this->assertSame(
            $objB,
            $idMap->getIdentity( 'RelationTestPerson', 42 )
        );
    }

    public function testGetIdentityMultipleRecordedDifferentClassSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $objA     = new RelationTestPerson();
        $objA->id = 23;
        
        $objB     = new RelationTestAddress();
        $objB->id = 42;

        $idMap->setIdentity( $objA );
        $idMap->setIdentity( $objB );

        $this->assertSame(
            $objA,
            $idMap->getIdentity( 'RelationTestPerson', 23 )
        );

        $this->assertSame(
            $objB,
            $idMap->getIdentity( 'RelationTestAddress', 42 )
        );
    }

    /*
     * setIdentity()
     */

    public function testSetIdentitySingleObjectSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );
    }

    public function testSetIdentityTowObjectsSameClassSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $objA     = new RelationTestPerson();
        $objA->id = 23;

        $objB     = new RelationTestPerson();
        $objB->id = 42;

        $idMap->setIdentity( $objA );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $objA
            )]],
            'identities',
            $idMap
        );

        $idMap->setIdentity( $objB );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $objA
            ), 42 => new ezcPersistentIdentity(
                $objB
            )]],
            'identities',
            $idMap
        );
    }

    public function testSetIdentityTowObjectsDifferentClassSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $objA     = new RelationTestPerson();
        $objA->id = 23;

        $objB     = new RelationTestAddress();
        $objB->id = 23;

        $idMap->setIdentity( $objA );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $objA
            )]],
            'identities',
            $idMap
        );

        $idMap->setIdentity( $objB );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $objA
            )], 'RelationTestAddress' => [23 => new ezcPersistentIdentity(
                $objB
            )]],
            'identities',
            $idMap
        );
    }

    public function testSetIdentityMissingDefinitionFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );

        $obj     = new stdClass();
        $obj->id = 23;

        try
        {
            $idMap->setIdentity( $obj );
            $this->fail( 'Exception not thrown on missing persistence definition.' );
        }
        catch( ezcPersistentDefinitionNotFoundException $e ) {}
    }

    public function testSetIdentitySameObjectTwiceSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );

        $idMap->setIdentity( $obj );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );
    }

    public function testSetIdentityEqualObjectTwiceFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $objA     = new RelationTestPerson();
        $objA->id = 23;
        
        $objB     = new RelationTestPerson();
        $objB->id = 23;

        $idMap->setIdentity( $objA );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $objA
            )]],
            'identities',
            $idMap
        );

        $idMap->setIdentity( $objB );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $objB
            )]],
            'identities',
            $idMap
        );
    }

    /*
     * removeIdentity()
     */

    public function testRemoveIdentityNotReferenced()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );

        $idMap->removeIdentity( 'RelationTestPerson', 23 );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => []],
            'identities',
            $idMap
        );
    }

    public function testRemoveIdentityReferenced()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );
        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'set_name' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );
        
        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects )],
                ['set_name' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][42]->references )
        );
        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][65]->references )
        );

        $idMap->removeIdentity( 'RelationTestAddress', 42 );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertFalse( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );
        
        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( [65 => $relatedObjects[65]] )],
                ['set_name' => new ArrayObject( [65 => $relatedObjects[65]] )]
            )], 'RelationTestAddress' => [65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][65]->references )
        );
    }

    /*
     * setRelatedObjects()
     */

    public function testSetRelatedObjectsWithoutNameNotExistsSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = new ArrayObject();
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects->getArrayCopy(), 'RelationTestAddress' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => $relatedObjects]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][42]->references )
        );
        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][65]->references )
        );
    }

    public function testSetRelatedObjectsWithRelationNameNotExistsSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new MultiRelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = new ArrayObject();
        $relatedObjects[42] = new MultiRelationTestPerson();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new MultiRelationTestPerson();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects(
            $obj,
            $relatedObjects->getArrayCopy(),
            'MultiRelationTestPerson',
            'fathers_children'
        );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][42]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][65]->references ) );

        $this->assertEquals(
            ['MultiRelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['MultiRelationTestPerson__fathers_children' => $relatedObjects]
            ), 42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['MultiRelationTestPerson'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['MultiRelationTestPerson'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1, count( $identities['MultiRelationTestPerson'][42]->references )
        );
        $this->assertEquals(
            1, count( $identities['MultiRelationTestPerson'][65]->references )
        );
    }

    public function testSetRelatedObjectsWithNameNotExsistsSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'set_name' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                [],
                ['set_name' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][42]->references )
        );
        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][65]->references )
        );
    }

    public function testSetRelatedObjectsTwiceWithDifferentNamesNotExistSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'first_set' );
        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'second_set' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                [],
                ['first_set' => new ArrayObject( $relatedObjects ), 'second_set' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            2, count( $identities['RelationTestAddress'][42]->references )
        );
        $this->assertEquals(
            2, count( $identities['RelationTestAddress'][65]->references )
        );
    }

    public function testSetRelatedObjectsMissingIdentitySuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity( $obj, ['RelationTestAddress' => new ArrayObject( $relatedObjects )] )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][42]->references )
        );
        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][65]->references )
        );
    }

    public function testSetRelatedObjectsWithoutNameAlreadyExistSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );
        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity( $obj, ['RelationTestAddress' => new ArrayObject( $relatedObjects )] )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][42]->references )
        );
        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][65]->references )
        );
    }

    public function testSetRelatedObjectsWithNameAlreadyExistSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'set_name' );
        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'set_name' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                [],
                ['set_name' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][42]->references )
        );
        $this->assertEquals(
            1, count( $identities['RelationTestAddress'][65]->references )
        );
    }

    public function testSetRelatedObjectsWithNameAlreadyExistReplaceIdentitiesSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $oldRelatedObjects = [];
        $oldRelatedObjects[42] = new RelationTestAddress();
        $oldRelatedObjects[42]->id = 42;


        $newRelatedObjects = [];
        $newRelatedObjects[42] = new RelationTestAddress();
        $newRelatedObjects[42]->id = 42;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $oldRelatedObjects[42] );

        $idMap->setRelatedObjectSet( $obj, $oldRelatedObjects, 'set_name' );

        $this->assertSame(
            $oldRelatedObjects[42],
            $idMap->getIdentity( 'RelationTestAddress', 42 )
        );
        $this->assertNotSame(
            $newRelatedObjects[42],
            $idMap->getIdentity( 'RelationTestAddress', 42 )
        );

        $idMap->setRelatedObjectSet( $obj, $newRelatedObjects, 'set_name', true );

        $this->assertNotSame(
            $oldRelatedObjects[42],
            $idMap->getIdentity( 'RelationTestAddress', 42 )
        );
        $this->assertSame(
            $newRelatedObjects[42],
            $idMap->getIdentity( 'RelationTestAddress', 42 )
        );
    }

    public function testSetRelatedObjectsInconsistentFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestEmployer();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        try
        {
            $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );
            $this->fail( 'Exception not thrown on inconsistent related object set.' );
        }
        catch ( ezcPersistentIdentityRelatedObjectsInconsistentException $e ) {}

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestEmployer'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity( $obj )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            )], 'RelationTestEmployer' => [65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestEmployer'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            0,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            0,
            count( $identities['RelationTestEmployer'][65]->references ),
            'Rel count of invalid object incorrect,'
        );
    }

    public function testSetUnnamedRelatedObjectsMissingIdentityFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $relObj     = new RelationTestAddress();
        $relObj->id = 42;
        
        try
        {
            $idMap->setRelatedObjects( $obj, [$relObj], 'RelationTestAddress' );
            $this->fail( 'Exception not thrown on setting related objects for unknown identity.' );
        }
        catch ( ezcPersistentIdentityMissingException $e ) {}
    }

    public function testSetNamedRelatedObjectsSetMissingIdentityFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $relObj     = new RelationTestAddress();
        $relObj->id = 42;
        
        try
        {
            $idMap->setRelatedObjectSet( $obj, [$relObj], 'named_set' );
            $this->fail( 'Exception not thrown on setting related objects for unknown identity.' );
        }
        catch ( ezcPersistentIdentityMissingException $e ) {}
    }

    /*
     * addRelatedObject()
     */

    public function testAddRelatedObjectToExistingSetSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $newRelatedObject     = new RelationTestAddress();
        $newRelatedObject->id = 3;
        
        $idMap->setIdentity( $newRelatedObject );
        $idMap->addRelatedObject( $obj, $newRelatedObject );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][3]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects + [3 => $newRelatedObject] )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            ), 3  => new ezcPersistentIdentity(
                $newRelatedObject,
                [],
                [],
                $identities['RelationTestAddress'][3]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][3]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    public function testAddRelatedObjectWithRelationNameToExistingSetSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new MultiRelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new MultiRelationTestPerson();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new MultiRelationTestPerson();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects(
            $obj,
            $relatedObjects,
            'MultiRelationTestPerson',
            'mothers_children'
        );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][42]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][65]->references ) );

        $this->assertEquals(
            ['MultiRelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['MultiRelationTestPerson__mothers_children' => new ArrayObject( $relatedObjects )]
            ), 42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['MultiRelationTestPerson'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['MultiRelationTestPerson'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $newRelatedObject     = new MultiRelationTestPerson();
        $newRelatedObject->id = 3;
        
        $idMap->setIdentity( $newRelatedObject );
        $idMap->addRelatedObject( $obj, $newRelatedObject, 'mothers_children' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][42]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][65]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][3]->references ) );

        $this->assertEquals(
            ['MultiRelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['MultiRelationTestPerson__mothers_children' => new ArrayObject( $relatedObjects + [3 => $newRelatedObject] )]
            ), 42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['MultiRelationTestPerson'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['MultiRelationTestPerson'][65]->references
            ), 3  => new ezcPersistentIdentity(
                $newRelatedObject,
                [],
                [],
                $identities['MultiRelationTestPerson'][3]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][65]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][3]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    public function testAddRelatedObjectIgnoredEmptySetSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $idMap->setIdentity( $obj );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );

        $newRelatedObject     = new RelationTestAddress();
        $newRelatedObject->id = 3;
        
        $idMap->setIdentity( $newRelatedObject );

        $idMap->addRelatedObject( $obj, $newRelatedObject );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )], 'RelationTestAddress' => [3  => new ezcPersistentIdentity( $newRelatedObject )]],
            'identities',
            $idMap
        );
    }

    public function testAddRelatedObjectInvalidateNamedSetsSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );
        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'named_set' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects )],
                ['named_set' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $newRelatedObject     = new RelationTestAddress();
        $newRelatedObject->id = 3;
        
        $idMap->setIdentity( $newRelatedObject );
        $idMap->addRelatedObject( $obj, $newRelatedObject );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][3]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects + [3 => $newRelatedObject] )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            ), 3  => new ezcPersistentIdentity(
                $newRelatedObject,
                [],
                [],
                $identities['RelationTestAddress'][3]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][3]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    public function testAddRelatedObjectMissingSrcIdentityFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $newRelatedObject     = new RelationTestAddress();
        $newRelatedObject->id = 3;
        
        try
        {
            $idMap->addRelatedObject( $obj, $newRelatedObject );
            $this->fail( 'Exception not thrown on setting related objects for unknown identity.' );
        }
        catch ( ezcPersistentIdentityMissingException $e ) {}
    }

    public function testAddRelatedObjectMissingDestIdentityFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $idMap->setIdentity( $obj );

        $newRelatedObject     = new RelationTestAddress();
        $newRelatedObject->id = 3;
        
        try
        {
            $idMap->addRelatedObject( $obj, $newRelatedObject );
            $this->fail( 'Exception not thrown on add of related object where the identity is missing.' );
        }
        catch ( ezcPersistentIdentityMissingException $e ) {}

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );
    }

    public function testAddRelatedObjectMissingDefinitionFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $idMap->setIdentity( $obj );

        $newRelatedObject     = new stdClass();
        $newRelatedObject->id = 3;
        
        try
        {
            $idMap->addRelatedObject( $obj, $newRelatedObject );
            $this->fail( 'Exception not thrown on missing definition of a related object.' );
        }
        catch ( ezcPersistentDefinitionNotFoundException $e ) {}

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );
    }

    public function testAddRelatedObjectTwiceFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );

        $newRelatedObject     = new RelationTestAddress();
        $newRelatedObject->id = 3;
        
        $idMap->setIdentity( $newRelatedObject );
        $idMap->addRelatedObject( $obj, $newRelatedObject );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][3]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects + [3 => $newRelatedObject] )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            ), 3  => new ezcPersistentIdentity(
                $newRelatedObject,
                [],
                [],
                $identities['RelationTestAddress'][3]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][3]->references ),
            'Rel count of valid object incorrect,'
        );

        try
        {
            $idMap->addRelatedObject( $obj, $newRelatedObject );
            $this->fail( 'Exception not thrown on double add of same new related object.' );
        }
        catch( ezcPersistentIdentityRelatedObjectAlreadyExistsException $e ) {}

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][3]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects + [3 => $newRelatedObject] )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            ), 3  => new ezcPersistentIdentity(
                $newRelatedObject,
                [],
                [],
                $identities['RelationTestAddress'][3]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][3]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    /*
     * removeRelatedObject()
     */

    public function testRemoveRelatedObjectSingleSetSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );
        
        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $idMap->removeRelatedObject( $obj, $relatedObjects[42] );
        
        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( [65 => $relatedObjects[65]] )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            0,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    public function testRemoveRelatedObjectNamedRelationSingleSetSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new MultiRelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new MultiRelationTestPerson();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new MultiRelationTestPerson();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects(
            $obj,
            $relatedObjects,
            'MultiRelationTestPerson',
            'fathers_children'
        );
        
        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][42]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][65]->references ) );

        $this->assertEquals(
            ['MultiRelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['MultiRelationTestPerson__fathers_children' => new ArrayObject( $relatedObjects )]
            ), 42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['MultiRelationTestPerson'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['MultiRelationTestPerson'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $idMap->removeRelatedObject( $obj, $relatedObjects[42], 'fathers_children' );
        
        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][42]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][65]->references ) );

        $this->assertEquals(
            ['MultiRelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['MultiRelationTestPerson__fathers_children' => new ArrayObject( [65 => $relatedObjects[65]] )]
            ), 42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['MultiRelationTestPerson'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['MultiRelationTestPerson'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            0,
            count( $identities['MultiRelationTestPerson'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][65]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    public function testRemoveRelatedObjectMultipleSetsSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );
        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'set_name' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );
        
        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects )],
                ['set_name' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $idMap->removeRelatedObject( $obj, $relatedObjects[42] );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( [65 => $relatedObjects[65]] )],
                ['set_name' => new ArrayObject( [65 => $relatedObjects[65]] )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            0,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    public function testRemoveRelatedObjectRelationNameMultipleSetsSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new MultiRelationTestPerson();
        $obj->id = 23;
        
        $relatedObjects = [];
        $relatedObjects[42] = new MultiRelationTestPerson();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new MultiRelationTestPerson();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'MultiRelationTestPerson', 'mothers_children' );
        $idMap->setRelatedObjects( $obj, $relatedObjects, 'MultiRelationTestPerson', 'fathers_children' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][42]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][65]->references ) );
        
        $this->assertEquals(
            ['MultiRelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['MultiRelationTestPerson__mothers_children' => new ArrayObject( $relatedObjects ), 'MultiRelationTestPerson__fathers_children' => new ArrayObject( $relatedObjects )]
            ), 42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['MultiRelationTestPerson'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['MultiRelationTestPerson'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            2,
            count( $identities['MultiRelationTestPerson'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            2,
            count( $identities['MultiRelationTestPerson'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $idMap->removeRelatedObject( $obj, $relatedObjects[42], 'fathers_children' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][42]->references ) );
        $this->assertTrue( isset( $identities['MultiRelationTestPerson'][65]->references ) );

        $this->assertEquals(
            ['MultiRelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['MultiRelationTestPerson__fathers_children' => new ArrayObject( [65 => $relatedObjects[65]] ), 'MultiRelationTestPerson__mothers_children' => new ArrayObject( $relatedObjects )]
            ), 42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['MultiRelationTestPerson'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['MultiRelationTestPerson'][65]->references
            )]],
            $identities
        );

        $this->assertEquals(
            1,
            count( $identities['MultiRelationTestPerson'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            2,
            count( $identities['MultiRelationTestPerson'][65]->references ),
            'Rel count of valid object incorrect,'
        );
    }

    public function testRemoveRelatedObjectNotExistsSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );
        
        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );
        
        $relatedObject = new RelationTestAddress();
        $relatedObject->id = 42;

        $idMap->removeRelatedObject( $obj, $relatedObject );

        $this->assertAttributeEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj
            )]],
            'identities',
            $idMap
        );
    }

    public function testRemoveRelatedObjectMissingSrcIdentityFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;
        
        $newRelatedObject     = new RelationTestAddress();
        $newRelatedObject->id = 3;
        
        try
        {
            $idMap->removeRelatedObject( $obj, $newRelatedObject );
            $this->fail( 'Exception not thrown on setting related objects for unknown identity.' );
        }
        catch ( ezcPersistentIdentityMissingException $e ) {}
    }

    /*
     * getRelatedObjects()
     */

    public function testGetRelatedObjectsUnnamedSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );

        $this->assertEquals(
            $relatedObjects,
            $idMap->getRelatedObjects( $obj, 'RelationTestAddress' )
        );
    }

    public function testGetRelatedObjectsRelationNameSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new MultiRelationTestPerson();
        $obj->id = 23;

        $relatedObjects = [];
        $relatedObjects[42] = new MultiRelationTestPerson();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new MultiRelationTestPerson();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects(
            $obj,
            $relatedObjects,
            'MultiRelationTestPerson',
            'mothers_children'
        );
        $idMap->setRelatedObjects(
            $obj,
            [],
            'MultiRelationTestPerson',
            'fathers_children'
        );

        $this->assertEquals(
            $relatedObjects,
            $idMap->getRelatedObjects( $obj, 'MultiRelationTestPerson', 'mothers_children' )
        );
        $this->assertEquals(
            [],
            $idMap->getRelatedObjects( $obj, 'MultiRelationTestPerson', 'fathers_children' )
        );
    }


    public function testGetRelatedObjectsNamedSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'set_name' );

        $this->assertEquals(
            $relatedObjects,
            $idMap->getRelatedObjectSet( $obj, 'set_name' )
        );
    }

    public function testGetRelatedObjectsUnnamedNotExistSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        $this->assertEquals(
            null,
            $idMap->getRelatedObjects( $obj, 'RelationTestAddress' )
        );
    }

    public function testGetRelatedObjectsNamedNotExistSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        $this->assertEquals(
            null,
            $idMap->getRelatedObjectSet( $obj,'set_name' )
        );
    }

    public function testGetRelatedObjectsUnnamedSrcNotExistSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $this->assertEquals(
            null,
            $idMap->getRelatedObjects( $obj, 'RelationTestAddress' )
        );
    }

    public function testGetRelatedObjectsNamedSrcNotExistSuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $this->assertEquals(
            null,
            $idMap->getRelatedObjectSet( $obj,'set_name' )
        );
    }

    public function testGetRelatedObjectsUnnamedEmptySuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        $this->assertEquals(
            null,
            $idMap->getRelatedObjects( $obj, 'RelationTestAddress' )
        );
    }

    public function testGetRelatedObjectsNamedEmptySuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        $this->assertEquals(
            null,
            $idMap->getRelatedObjectSet( $obj, 'set_name' )
        );
    }

    public function testGetRelatedObjectsUnknownClassFailure()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $idMap->setIdentity( $obj );

        try
        {
            $idMap->getRelatedObjects( $obj, 'stdClass' );
            $this->fail( 'Exception not thrown on access to related objects with missing relation.' );
        }
        catch ( ezcPersistentRelationNotFoundException $e ) {}
    }

    /*
     * reset()
     */

    public function testResetEmptySuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );

        $this->assertAttributeEquals(
            [],
            'identities',
            $idMap
        );
        $this->assertAttributeSame(
            $this->definitionManager,
            'definitionManager',
            $idMap
        );

        $idMap->reset();

        $this->assertAttributeEquals(
            [],
            'identities',
            $idMap
        );
        $this->assertAttributeSame(
            $this->definitionManager,
            'definitionManager',
            $idMap
        );
    }

    public function testResetNonEmptySuccess()
    {
        $idMap = new ezcPersistentBasicIdentityMap(
            $this->definitionManager
        );
        
        $obj     = new RelationTestPerson();
        $obj->id = 23;

        $relatedObjects = [];
        $relatedObjects[42] = new RelationTestAddress();
        $relatedObjects[42]->id = 42;
        $relatedObjects[65] = new RelationTestAddress();
        $relatedObjects[65]->id = 65;

        $idMap->setIdentity( $obj );
        $idMap->setIdentity( $relatedObjects[42] );
        $idMap->setIdentity( $relatedObjects[65] );

        $idMap->setRelatedObjects( $obj, $relatedObjects, 'RelationTestAddress' );
        $idMap->setRelatedObjectSet( $obj, $relatedObjects, 'set_name' );

        $identities = $this->readAttribute(
            $idMap, 'identities'
        );

        $this->assertTrue( isset( $identities['RelationTestAddress'][42]->references ) );
        $this->assertTrue( isset( $identities['RelationTestAddress'][65]->references ) );

        $this->assertEquals(
            ['RelationTestPerson' => [23 => new ezcPersistentIdentity(
                $obj,
                ['RelationTestAddress' => new ArrayObject( $relatedObjects )],
                ['set_name' => new ArrayObject( $relatedObjects )]
            )], 'RelationTestAddress' => [42 => new ezcPersistentIdentity(
                $relatedObjects[42],
                [],
                [],
                $identities['RelationTestAddress'][42]->references
            ), 65 => new ezcPersistentIdentity(
                $relatedObjects[65],
                [],
                [],
                $identities['RelationTestAddress'][65]->references
            )]],
            $identities
        );
        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][42]->references ),
            'Rel count of valid object incorrect,'
        );
        $this->assertEquals(
            2,
            count( $identities['RelationTestAddress'][65]->references ),
            'Rel count of valid object incorrect,'
        );

        $this->assertAttributeSame(
            $this->definitionManager,
            'definitionManager',
            $idMap
        );

        $idMap->reset();

        $this->assertAttributeEquals(
            [],
            'identities',
            $idMap
        );
        $this->assertAttributeSame(
            $this->definitionManager,
            'definitionManager',
            $idMap
        );
    }
}

?>
