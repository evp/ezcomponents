<?php
/**
 * ezcCacheStorageFilePlainTest
 * 
 * @package Cache
 * @subpackage Tests
 * @version 1.5
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Require parent test class. 
 */
require_once 'storage_test.php';

/**
 * Test suite for ezcStorageFilePlain class. 
 * 
 * @package Cache
 * @subpackage Tests
 */
class ezcCacheStorageFilePlainTest extends ezcCacheStorageTest
{
    /**
     * data 
     * 
     * @var array
     * @access protected
     */
    protected $testData = [1 => "Test 1 2 3 4 5 6 7 8\\\\", 2 => 'La la la 02064 lololo', 3 => 12345, 4 => 12.3746];

    public function testGetRemainingLifetimeId()
    {
        $this->storage->setOptions( ['ttl' => 10] );

        $this->storage->store( '1', 'data1' );

        $this->assertEquals( true, 8 < $this->storage->getRemainingLifetime( '1' ) );

    }

    public function testGetRemainingLifetimeAttributes()
    {
        $this->storage->setOptions( ['ttl' => 10] );

        $this->storage->store( '1', 'data1', ['type' => 'simple'] );
        $this->storage->store( '2', 'data2', ['type' => 'simple'] );

        $this->assertEquals( true, 8 < $this->storage->getRemainingLifetime( null, ['type' => 'simple'] ) );

    }

    public function testGetRemainingLifetimeNoMatch()
    {
        $this->storage->setOptions( ['ttl' => 10] );

        $this->assertEquals( 0, $this->storage->getRemainingLifetime( 'no_such_id' ) );

    }

    public function testMetaDataSuccess()
    {
        $temp = $this->createTempDir( self::class );

        $meta = new ezcCacheStackLruMetaData();
        $meta->setState(
            ['replacementData' => ['id_1' => 23, 'id_2' => 42], 'storageData' => ['storage_id_1' => ['id_1' => true, 'id_2' => true], 'storage_id_2' => ['id_2' => true]]]
        );

        $storage = new ezcCacheStorageFilePlain( $temp );

        $this->assertFalse(
            file_exists( $storage->getLocation() . $storage->options->metaDataFile ),
            'Meta data file existed before the storage was created.'
        );

        $storage->storeMetaData( $meta );

        $this->assertTrue(
            file_exists( $storage->getLocation() . $storage->options->metaDataFile ),
            'Meta data file existed before the storage was created.'
        );

        $restoredMeta = $storage->restoreMetaData();

        $this->assertEquals(
            $meta,
            $restoredMeta,
            'Meta data not restored correctly.'
        );

        $this->assertTrue(
            file_exists( $storage->getLocation() . $storage->options->metaDataFile ),
            'Meta data does not exist anymore after restoring.'
        );
    }

    public function testMetaDataFailure()
    {
        $temp = $this->createTempDir( self::class );

        $storage = new ezcCacheStorageFilePlain( $temp );

        $this->assertFalse(
            file_exists( $storage->getLocation() . $storage->options->metaDataFile ),
            'Meta data file existed before the storage was created.'
        );

        $restoredMeta = $storage->restoreMetaData();

        $this->assertNull(
            $restoredMeta,
            'Meta data not restored correctly.'
        );

        $this->assertFalse(
            file_exists( $storage->getLocation() . $storage->options->metaDataFile ),
            'Meta data file existed before the storage was created.'
        );
    }

    public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcCacheStorageFilePlainTest" );
	}
}
?>
