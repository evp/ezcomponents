<?php
/**
 * ezcCacheStorageTest 
 * 
 * @package Cache
 * @subpackage Tests
 * @version 1.5
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**;
 * Test suite for ezcStorageFile class.
 * 
 * @package Cache
 * @subpackage Tests
 */
class ezcCacheStorageFileTest extends ezcTestCase
{
    public function testGenerateIdentifier1()
    {
        $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ) );
        $id = $obj->generateIdentifier( 'contentstructuremenu/show_content_structure-2 file:foobar' );
        $this->assertEquals( 'contentstructuremenu'.DIRECTORY_SEPARATOR.'show_content_structure-2_file:foobar-.cache', $id );
    }

    public function testGenerateIdentifier2()
    {
        $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ) );
        $id = $obj->generateIdentifier( 'contentstructuremenu\show_content_structure-2 file:foobar' );
        $this->assertEquals( 'contentstructuremenu'.DIRECTORY_SEPARATOR.'show_content_structure-2_file:foobar-.cache', $id );
    }

    public function testGenerateIdentifier3()
    {
        $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ), ['extension' => '.c'] );
        $id = $obj->generateIdentifier( 'contentstructuremenu\show_content_structure-2 file:foobar' );
        $this->assertEquals( 'contentstructuremenu'.DIRECTORY_SEPARATOR.'show_content_structure-2_file:foobar-.c', $id );
    }

    public function testGenerateIdentifier4()
    {
        $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ), ['extension' => '.c'] );
        $id = $obj->generateIdentifier( 1 );
        $this->assertEquals( '1-.c', $id );
    }

    public function testGenerateIdentifier5()
    {
        $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ), ['extension' => '.c'] );
        $id = $obj->generateIdentifier( 1, ["foo" => "bar", "baz" => "bam"] );
        $this->assertEquals( '1-baz=bam-foo=bar.c', $id );
    }

    public function testInvalidConfigurationOption()
    {
        try
        {
            $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ), ['eXtEnSiOn' => '.c'] );
            $this->fail( 'Expected exception was not thrown' );
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
    }

    public function testCountDataItems()
    {
        $cache = new ezcCacheStorageFileArray( $this->createTempDir( 'ezcCacheStorageFileTest' ), ['extension' => '.c'] );
        $data = [['attributes' => ['lang' => 'en', 'section' => 'articles'], 'content'    => ['lang' => 'en', 'section' => 'articles']], ['attributes' => ['lang' => 'de', 'section' => 'articles'], 'content'    => ['lang' => 'de', 'section' => 'articles']], ['attributes' => ['lang' => 'no', 'section' => 'articles'], 'content'    => ['lang' => 'no', 'section' => 'articles']]];
        foreach ( $data as $id => $dataArr )
        {
            $cache->store( $id, $dataArr['content'], $dataArr['attributes'] );
        }

        $this->assertEquals( $cache->countDataItems( 0 ), 1, 'Count data items failed with ID.' );
        $this->assertEquals( $cache->countDataItems( null, ['lang' => 'no'] ), 1, 'Count data items failed with attribute <lang>.' );
        $this->assertEquals( $cache->countDataItems( null, ['section' => 'articles'] ), 3, 'Count data items failed with attribute <articles>.' );

        $this->removeTempDir();
    }

    public function testFalseLifetime()
    {
        $cache = new ezcCacheStorageFileArray(
            $this->createTempDir( 'ezcCacheStorageFileTest' ), 
            ['extension' => '.c', 'ttl' => false]
        );
        $data = ['attributes' => ['lang' => 'en', 'section' => 'articles'], 'content'    => ['lang' => 'en', 'section' => 'articles']];

        $cache->store( 0, $data['attributes'], $data['content'] );

        $file = $cache->generateIdentifier( 0, $data['attributes'] );
        // Fake mtime and atime
        touch( $cache->getLocation() . '/' . $file, time() - 90000, time() - 90000 );
        
        $this->assertNotEquals( false, $cache->restore( 0, $data['attributes'] ) );

        $this->removeTempDir();
    }

    public function testDeleteRecursive()
    {
        $tempDir = $this->createTempDir( 'ezcCacheStorageFileTest' );
        $cache = new ezcCacheStorageFileArray( $tempDir, ['extension' => '.c'] );
        $data = ["foo" => ['attributes' => ['lang' => 'en', 'section' => 'articles'], 'content'    => ['lang' => 'en', 'section' => 'articles']], "foo/bar" => ['attributes' => ['lang' => 'de', 'section' => 'articles'], 'content'    => ['lang' => 'de', 'section' => 'articles']], "foo/baz" => ['attributes' => ['lang' => 'no', 'section' => 'articles'], 'content'    => ['lang' => 'no', 'section' => 'articles']]];

        foreach ( $data as $id => $dataArr )
        {
            $cache->store( $id, $dataArr['content'], $dataArr['attributes'] );
        }

        $deleted = $cache->delete( null, ["section" => "articles"], true );

        $this->assertEquals(
            array_keys( $data ),
            $deleted,
            'Deleted keys not returned correctly from delete().'
        );

        $this->removeTempDir();
    }

    public function testPermissions()
    {
        $cache = new ezcCacheStorageFileArray(
            $this->createTempDir( 'ezcCacheStorageFileTest' ), 
            ['extension' => '.c', 'ttl' => false]
        );
        $data = ['attributes' => ['lang' => 'en', 'section' => 'articles'], 'content'    => ['lang' => 'en', 'section' => 'articles']];

        $cache->store( 0, $data['attributes'], $data['content'] );
        $file = $cache->getLocation() . "/" . $cache->generateIdentifier( 0, $data['attributes'] );
        
        $this->assertEquals( 0644, ( fileperms( $file ) & 0777 ) );

        $cache->options->permissions = 0777;

        $cache->store( 1, $data['attributes'], $data['content'] );
        $file = $cache->getLocation() . "/" . $cache->generateIdentifier( 1, $data['attributes'] );

        $this->assertEquals( 0777,  ( fileperms( $file ) & 0777 ) );
        
        $this->removeTempDir();
    }

    public function testRestoreWithoutSearch()
    {
        $cache = new ezcCacheStorageFileArray(
            $this->createTempDir( 'ezcCacheStorageFileTest' ), 
            ['extension' => '.c', 'ttl' => false]
        );

        $id = "test";
        $keys = [10000, 1, 10, 100, 1000];
        
        // Store
        foreach ( $keys as $key )
        {
            // No cache may exist!
            $this->assertFalse(
                $cache->restore( $id, [0 => $key, 1 => "en"], false )
            );
            $cache->store( $id, "ID=$key&LANG=en", [0 => $key, 1 => "en"] );
        }

        // Restore
        foreach ( $keys as $key )
        {
            $this->assertEquals(
                $cache->restore( $id, [0 => $key, 1 => "en"], false ),
                "ID=$key&LANG=en"
            );
        }

        $this->removeTempDir();
    }
    
    public function testCreateCacheFailUnreadable()
    {
        $temp = $this->createTempDir( self::class );
        $location = $temp . DIRECTORY_SEPARATOR . 'subpath';
        mkdir( $location );
        chmod( $location, 0 );
        try
        {
            new ezcCacheStorageFilePlain( $location );
            $this->fail( "Expected exception was not thrown." );
        }
        catch ( ezcBaseFilePermissionException $e )
        {
            $this->assertEquals( true, strpos( $e->getMessage(), "Cache location is not readable" ) !== false );
        }

        chmod( $location, 0777 );
        $this->removeTempDir( $temp );
    }

    public function testCreateCacheFailUnwritable()
    {
        $temp = $this->createTempDir( self::class );
        $location = $temp . DIRECTORY_SEPARATOR . 'subpath';
        mkdir( $location );
        chmod( $location, 0444 );
        try
        {
            new ezcCacheStorageFilePlain( $location );
            $this->fail( "Expected exception was not thrown." );
        }
        catch ( ezcBaseFilePermissionException $e )
        {
            $this->assertEquals( true, strpos( $e->getMessage(), "Cache location is not writeable" ) !== false );
        }

        chmod( $location, 0777 );
        $this->removeTempDir( $temp );
    }

    /**
     * testStoreRestoreNotoutdatedWithoutAttributes 
     * 
     * @access public
     */
    public function testStoreRestoreNotoutdatedWithoutAttributes()
    {
        // Test with 10 seconds lifetime
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 10] );
        foreach ( $this->data as $id => $dataArr ) 
        {
            $filename = $storage->getLocation() . $storage->generateIdentifier( $id );

            $storage->store( $id, $dataArr );
            // Faking the m/a-time to be 5 seconds in the past
            touch( $filename, ( time()  - 5 ), ( time()  - 5 ) );
            
            $data = $storage->restore( $id );
            $this->assertEquals(
                $dataArr,
                $data,
                "Restore data broken for ID <{$id}>."
            );
        }
        $this->removeTempDir();
    }

    /**
     * testStoreRestoreNotoutdatedWithAttributes 
     * 
     * @access public
     */
    public function testStoreRestoreNotoutdatedWithAttributes()
    {
        // Test with 10 seconds lifetime
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 10] );
        
        $dataArr = ['1', '2', '3'];
        
        foreach ( $dataArr as $id => $data ) 
        {
            $attributes = ['name'      => 'test', 'title'     => 'Test item', 'date'      => time() . $id];
            
            $filename = $storage->getLocation() . $storage->generateIdentifier( $id, $attributes );
            
            $storage->store( $id, $data, $attributes );

            // Faking the m/a-time to be 5 seconds in the past
            touch( $filename, ( time() - 5 ), ( time() - 5 ) );
            
            $restoredData = $storage->restore( $id, $attributes );

            $this->assertEquals(
                $data,
                $restoredData,
                "Restore data broken for ID <{$id}>."
            );
        }
        $this->removeTempDir();
    }

    public function testPurgeSimpleNoLimit()
    {
        // Test with 30 seconds lifetime
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 30] );

        $dataArr = ['0', '1', '2'];
        
        foreach ( $dataArr as $id => $data ) 
        {
            $filename = $storage->getLocation() . $storage->generateIdentifier( $id );
            
            $storage->store( $id, $data );

            // Faking the m/a-time to be 60 seconds in the past
            touch( $filename, ( time() - 60 ), ( time() - 60 ) );
        }

        // Add items which will not be purged
        $storage->store( 3, '3' );
        $storage->store( 4, '4' );

        $purged = $storage->purge();

        $this->assertEquals(
            ['0', '1', '2'],
            $purged,
            'Purged incorrect IDs'
        );

        $this->assertEquals(
            2,
            $storage->countDataItems()
        );

        $this->removeTempDir();
    }

    public function testPurgeComplexNoLimit()
    {
        // Test with 30 seconds lifetime
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 30] );

        $outdatedData = ['ID', 'Some/Dir/ID', 'Some/other/Dir/ID/1', 'Some/other/Dir/ID/2', 'Some/other/Dir/ID/3'];
        foreach ( $outdatedData as $id ) 
        {
            $filename = $storage->getLocation() . $storage->generateIdentifier( $id );
            
            $storage->store( $id, $id );

            // Faking the m/a-time to be 60 seconds in the past
            touch( $filename, ( time() - 60 ), ( time() - 60 ) );
        }

        $data = ['otherID', 'Some/Dir/otherID', 'Some/other/Dir/ID/4', 'Some/other/Dir/ID/5', 'Some/other/Dir/ID/6'];
        foreach ( $data as $id ) 
        {
            $storage->store( $id, $id );
        }

        $this->assertEquals(
            10,
            $storage->countDataItems()
        );

        $purged = $storage->purge();

        $this->assertEquals(
            $outdatedData,
            $purged,
            'Purged incorrect IDs'
        );

        $this->assertEquals(
            5,
            $storage->countDataItems()
        );

        $this->removeTempDir();
    }

    public function testPurgeSimpleLimit()
    {
        // Test with 30 seconds lifetime
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 30] );

        $dataArr = ['0', '1', '2'];
        
        foreach ( $dataArr as $id => $data ) 
        {
            $filename = $storage->getLocation() . $storage->generateIdentifier( $id );
            
            $storage->store( $id, $data );

            // Faking the m/a-time to be 60 seconds in the past
            touch( $filename, ( time() - 60 ), ( time() - 60 ) );
        }

        // Add items which will not be purged
        $storage->store( 3, '3' );
        $storage->store( 4, '4' );

        $purged = $storage->purge( 2 );

        $this->assertEquals(
            ['0', '1'],
            $purged,
            'Purged incorrect IDs'
        );

        $this->assertEquals(
            3,
            $storage->countDataItems()
        );

        $this->removeTempDir();
    }

    public function testPurgeComplexLimit()
    {
        // Test with 30 seconds lifetime
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 30] );

        $outdatedData = ['ID', 'Some/Dir/ID', 'Some/other/Dir/ID/1', 'Some/other/Dir/ID/2', 'Some/other/Dir/ID/3'];
        foreach ( $outdatedData as $id ) 
        {
            $filename = $storage->getLocation() . $storage->generateIdentifier( $id );
            
            $storage->store( $id, $id );

            // Faking the m/a-time to be 60 seconds in the past
            touch( $filename, ( time() - 60 ), ( time() - 60 ) );
        }

        $data = ['otherID', 'Some/Dir/otherID', 'Some/other/Dir/ID/4', 'Some/other/Dir/ID/5', 'Some/other/Dir/ID/6'];
        foreach ( $data as $id ) 
        {
            $storage->store( $id, $id );
        }

        $this->assertEquals(
            10,
            $storage->countDataItems()
        );

        $purged = $storage->purge( 3 );

        $this->assertEquals(
            ['ID', 'Some/Dir/ID', 'Some/other/Dir/ID/1'],
            $purged,
            'Purged incorrect IDs'
        );

        $this->assertEquals(
            7,
            $storage->countDataItems()
        );

        $this->removeTempDir();
    }

    public function testResetSuccess()
    {
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 30] );

        $data = ['ID', 'Some/Dir/ID', 'Some/other/Dir/ID/1', 'Some/other/Dir/ID/2', 'Some/other/Dir/ID/3'];
        foreach ( $data as $id ) 
        {
            $storage->store( $id, $id );
        }

        $this->assertEquals(
            5,
            $storage->countDataItems()
        );

        $storage->reset();

        $this->assertEquals(
            0,
            $storage->countDataItems()
        );

        $this->removeTempDir();
    }

    public function testResetFailureTopFile()
    {
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp, ['ttl' => 30] );

        $data = ['ID', 'Some/Dir/ID', 'Some/other/Dir/ID/1', 'Some/other/Dir/ID/2', 'Some/other/Dir/ID/3'];
        foreach ( $data as $id ) 
        {
            $storage->store( $id, $id );
        }

        $this->assertEquals(
            5,
            $storage->countDataItems()
        );

        chmod( $storage->getLocation(), 0500 );
        clearstatcache();

        try
        {
            $storage->reset();
            $this->fail( 'Exception not thrown on non-successful delete.' );
        }
        catch ( ezcBaseFilePermissionException $e ) 
        {
            self::assertEquals( "The file '$temp/ID-.cache' can not be removed. (Could not unlink cache file.)", $e->getMessage() );
        }

        chmod( $storage->getLocation(), 0777 );
        chmod( $storage->getLocation() . '/Some', 0777 );
        chmod( $storage->getLocation() . '/Some/other', 0777 );
        chmod( $storage->getLocation() . '/Some/other/Dir', 0777 );

        clearstatcache();

        $this->removeTempDir();
    }

    public function testLockSimple()
    {
        $temp = $this->createTempDir( self::class );
        $storage = new ezcCacheStorageFilePlain( $temp );

        $this->assertFalse(
            file_exists( $storage->getLocation() . $storage->options->lockFile ),
            'Lock file exists.'
        );

        $this->assertFalse(
            $this->readAttribute( $storage, 'lockResource' ),
            'Lock resource not correctly initialized'
        );

        $storage->lock();

        $this->assertTrue(
            file_exists( $storage->getLocation() . $storage->options->lockFile ),
            'Lock file does not exist.'
        );

        $this->assertTrue(
            is_resource(
                $this->readAttribute( $storage, 'lockResource' )
            ),
            'Lock resource not correctly created'
        );

        $storage->unlock();

        $this->assertFalse(
            $this->readAttribute( $storage, 'lockResource' ),
            'Lock resource not correctly released'
        );

        $this->assertFalse(
            file_exists( $storage->getLocation() . $storage->options->lockFile ),
            'Lock file exists.'
        );

        $this->removeTempDir();
    }

    public function testLockTimeout()
    {
        $temp = $this->createTempDir( self::class );
        $opts = ['maxLockTime' => 1];
        $storage       = new ezcCacheStorageFilePlain( $temp, $opts );
        $secondStorage = new ezcCacheStorageFilePlain( $temp, $opts );

        $this->assertFalse(
            file_exists( $storage->getLocation() . $storage->options->lockFile ),
            'Lock file exists.'
        );

        $lockTime = time();
        $storage->lock();

        $this->assertTrue(
            file_exists( $storage->getLocation() . $storage->options->lockFile ),
            'Lock file does not exist.'
        );

        // Should kill lock file after 1 sec
        $secondStorage->lock();

        $this->assertTrue(
            ( time() - $lockTime ) > 1,
            'Lock did not last for 1 sec.'
        );

        // Lock file should exist again
        $this->assertTrue(
            file_exists( $storage->getLocation() . $storage->options->lockFile ),
            'Lock file does not exist.'
        );

        $secondStorage->unlock();

        $this->assertFalse(
            file_exists( $storage->getLocation() . $storage->options->lockFile ),
            'Lock file exists.'
        );

        $this->removeTempDir();
    }

    public function testMetaDataSuccess()
    {
        $temp = $this->createTempDir( self::class );

        $meta = new ezcCacheStackLruMetaData();
        $meta->setState(
            ['replacementData' => ['id_1' => 23, 'id_2' => 42], 'storageData' => ['storage_id_1' => ['id_1' => true, 'id_2' => true], 'storage_id_2' => ['id_2' => true]]]
        );

        $storage = new ezcCacheStorageFileArray( $temp );

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

        $this->removeTempDir();
    }

    public function testMetaDataFailure()
    {
        $temp = $this->createTempDir( self::class );

        $storage = new ezcCacheStorageFileArray( $temp );

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

        $this->removeTempDir();
    }

    public static function suite()
    {
         return new PHPUnit_Framework_TestSuite( "ezcCacheStorageFileTest" );
    }
}
?>
