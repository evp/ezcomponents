<?php
/**
 * ezcCacheStorageFileOptionsTest 
 * 
 * @package Cache
 * @subpackage Tests
 * @version 1.5
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */


/**
 * Abstract base test class for ezcCacheStorageFileOptions tests.
 * 
 * @package Cache
 * @subpackage Tests
 */
class ezcCacheStorageFileOptionsTest extends ezcTestCase
{
	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( "ezcCacheStorageFileOptionsTest" );
	}
    
    public function testConstructor()
    {
        $fake = new ezcCacheStorageFileOptions(
            ['ttl'          => 86400, 'extension'    => '.cache', 'permissions'  => 0644, 'lockFile'     => '.ezcLock', 'lockWaitTime' => 200000, 'maxLockTime'  => 5, 'metaDataFile' => '.ezcMetaData']
        );
        $this->assertEquals( 
            $fake,
            new ezcCacheStorageFileOptions(),
            'Default values incorrect for ezcCacheStorageFileOptions.'
        );
    }

    public function testNewAccess()
    {
        $opt = new ezcCacheStorageFileOptions();

        $this->assertEquals( $opt['ttl'], 86400 );
        $this->assertEquals( $opt['extension'], '.cache' );
        $this->assertEquals( $opt['permissions'], 0644 );
        $this->assertEquals( $opt['lockFile'], '.ezcLock' );
        $this->assertEquals( $opt['lockWaitTime'], 200000 );
        $this->assertEquals( $opt['maxLockTime'], 5 );
        $this->assertEquals( $opt['metaDataFile'], '.ezcMetaData' );
    }

    public function testGetAccessSuccess()
    {
        $opt = new ezcCacheStorageFileOptions();

        $this->assertEquals( $opt->ttl, 86400 );
        $this->assertEquals( $opt->extension, ".cache" );
        $this->assertEquals( $opt->permissions, 0644 );
        $this->assertEquals( $opt->lockFile, '.ezcLock' );
        $this->assertEquals( $opt->metaDataFile, '.ezcMetaData' );
    }

    public function testGetAccessFailure()
    {
        $opt = new ezcCacheStorageFileOptions();
        
        try
        {
            echo $opt->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "ezcBasePropertyNotFoundException not thrown on access to invalid property foo." );
    }

    public function testSetAccessSuccess()
    {
        $opt = new ezcCacheStorageFileOptions();

        $this->assertSetProperty(
            $opt,
            'ttl',
            [0, 23, false]
        );

        $this->assertSetProperty(
            $opt,
            'permissions',
            [0, 0777]
        );

        $this->assertSetProperty(
            $opt,
            'extension',
            ['.foo']
        );

        $this->assertSetProperty(
            $opt,
            'lockFile',
            ['.foo']
        );

        $this->assertSetProperty(
            $opt,
            'lockWaitTime',
            [100000]
        );

        $this->assertSetProperty(
            $opt,
            'maxLockTime',
            [10]
        );

        $this->assertSetProperty(
            $opt,
            'metaDataFile',
            ['.foo']
        );
    }

    public function testSetAccessFailure()
    {
        $opt = new ezcCacheStorageFileOptions();

        $this->assertSetPropertyFails(
            $opt,
            'ttl',
            [true, 23.42, 'foo', [], new stdClass()]
        );

        $this->assertSetPropertyFails(
            $opt,
            'permissions',
            [true, 23.42, 'foo', [], new stdClass()]
        );

        $this->assertSetPropertyFails(
            $opt,
            'extension',
            [true, false, 23.42, [], new stdClass()]
        );

        $this->assertSetPropertyFails(
            $opt,
            'lockFile',
            [true, false, 23.42, [], new stdClass()]
        );

        $this->assertSetPropertyFails(
            $opt,
            'lockWaitTime',
            [true, false, 23.42, [], new stdClass()]
        );

        $this->assertSetPropertyFails(
            $opt,
            'maxLockTime',
            [true, false, 23.42, [], new stdClass()]
        );

        $this->assertSetPropertyFails(
            $opt,
            'metaDataFile',
            [true, false, 23.42, [], new stdClass()]
        );

        try
        {
            $opt->foo = "bar";
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( "ezcBasePropertyNotFoundException not thrown on set access to invalid property." );
    }

    public function testIssetAccess()
    {
        $opt = new ezcCacheStorageFileOptions();
        
        $this->assertTrue( isset( $opt->ttl ) );
        $this->assertTrue( isset( $opt->extension ) );
        $this->assertTrue( isset( $opt->permissions ) );
        $this->assertTrue( isset( $opt->lockFile ) );
        $this->assertTrue( isset( $opt->lockWaitTime ) );
        $this->assertTrue( isset( $opt->maxLockTime ) );
        $this->assertTrue( isset( $opt->metaDataFile ) );

        $this->assertFalse( isset( $opt->foo ) );
    }

    public function testMergeOptions()
    {
        $options = new ezcCacheStorageFileOptions();
        $optionsNew = new ezcCacheStorageOptions();
        $optionsNew->ttl = 30;
        $options->mergeStorageOptions( $optionsNew );
        $this->assertEquals( 30, $options->ttl );
    }

    public function testOptions()
    {
        $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ) );
        $options = new ezcCacheStorageFileOptions();
        $optionsGeneral = new ezcCacheStorageOptions();
        
        $this->assertEquals( $options, $obj->getOptions() );

        $obj->options = $optionsGeneral;
        $this->assertEquals( $options, $obj->getOptions() );

        $obj->options = $options;
        $this->assertEquals( $options, $obj->getOptions() );

        $obj->setOptions( $optionsGeneral );
        $this->assertEquals( $options, $obj->getOptions() );

        $obj->setOptions( $options );
        $this->assertEquals( $options, $obj->getOptions() );

        try
        {
            $obj->setOptions( 'wrong value' );
            $this->fail( "Expected exception was not thrown." );
        }
        catch ( ezcBaseValueException $e )
        {
            $this->assertEquals(
                "The value 'wrong value' that you were trying to assign to "
                    . "setting 'options' is invalid. Allowed values are: " 
                    . "instance of ezcCacheStorageFileOptions or (deprecated) "
                    . "ezcCacheStorageOptions.",
                $e->getMessage()
            );
        }
    }

    public function testProperties()
    {
        $obj = new ezcCacheStorageFileArray( $this->createTempDir( self::class ) );
        $options = new ezcCacheStorageFileOptions();

        $this->assertTrue(
            isset( $obj->options )
        );

        $obj->options = $options;
        $this->assertSame(
            $options,
            $obj->options
        );

        $this->assertSetPropertyFails(
            $obj,
            'options',
            [true, false, 23, 42.23, 'foo', new stdClass()]
        );
    }

    protected function genericSetFailureTest( $obj, $property, $value )
    {
        try
        {
            $obj->$property = $value;
        }
        catch ( ezcBaseValueException $e )
        {
            return;
        }
        $this->fail( "ezcBaseValueException not thrown on invalid value '$value' for " . get_class( $obj ) . "->$property." );
    }
}
?>
