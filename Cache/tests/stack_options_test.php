<?php
/**
 * ezcCacheStackOptionsTest 
 * 
 * @package Cache
 * @subpackage Tests
 * @version 1.5
 * @copyright Copyright (C) 2005-2009 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Configurator class. 
 */
require_once 'stack_test_configurator.php';

/**
 * Test suite for the ezcCacheStackOptions class.
 * 
 * @package Cache
 * @subpackage Tests
 */
class ezcCacheStackOptionsTest extends ezcTestCase
{
    public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( self::class );
	}

    public function testCtorDefaultSuccess()
    {
        $opts = new ezcCacheStackOptions();
        $this->assertAttributeEquals(
            ['configurator'        => null, 'metaStorage'         => null, 'replacementStrategy' => 'ezcCacheStackLruReplacementStrategy', 'bubbleUpOnRestore'   => false],
            'properties',
            $opts,
            'Default options incorrect.'
        );
    }

    public function testCtorNonDefaultSuccess()
    {
        $optArray = [
            'configurator'        => 'ezcCacheStackTestConfigurator',
            // @TODO: Should be a valid storage object.
            'metaStorage'         => null,
            'replacementStrategy' => 'ezcCacheStackLfuReplacementStrategy',
            'bubbleUpOnRestore'   => true,
        ];
        $opts = new ezcCacheStackOptions( $optArray );
        $this->assertAttributeEquals(
            $optArray,
            'properties',
            $opts,
            'Options set via ctor incorrect.'
        );
    }

    public function testSetSuccess()
    {
        $metaDataStorage = $this->getMock( 'ezcCacheStackMetaDataStorage' );

        $opts = new ezcCacheStackOptions();
        $this->assertSetProperty(
            $opts,
            'configurator',
            ['ezcCacheStackTestConfigurator', null]
        );

        $this->assertSetProperty(
            $opts,
            'metaStorage',
            [$metaDataStorage]
        );
        $this->assertSetProperty(
            $opts,
            'replacementStrategy',
            ['ezcCacheStackLfuReplacementStrategy', 'ezcCacheStackLruReplacementStrategy']
        );
        $this->assertSetProperty(
            $opts,
            'bubbleUpOnRestore',
            [true, false]
        );
    }

    public function testSetFailure()
    {
        $nonMetaDataStorage = $this->getMock(
            'ezcCacheStorage',
            ['validateLocation', 'store', 'restore', 'delete', 'countDataItems', 'getRemainingLifetime'],
            [],
            '',
            false
        );

        $opts = new ezcCacheStackOptions();
        $this->assertSetPropertyFails(
            $opts,
            'configurator',
            [true, false, 23, 42.23, 'Foo', [], 'stdClass', new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'metaStorage',
            [true, false, 23, 42.23, 'Foo', [], 'stdClass', new stdClass(), $nonMetaDataStorage]
        );
        $this->assertSetPropertyFails(
            $opts,
            'replacementStrategy',
            [null, true, false, 23, 42.23, 'Foo', [], 'stdClass', new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opts,
            'bubbleUpOnRestore',
            [null, 23, 42.23, 'Foo', [], 'stdClass', new stdClass()]
        );

        try
        {
            $opts->fooBar = 23;
            $ths->fail( 'Exception not thrown on access to unknown option.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testGetSuccess()
    {
        $opts = new ezcCacheStackOptions();
        $this->assertEquals( null, $opts->configurator );
        $this->assertEquals( null, $opts->metaStorage );
        $this->assertEquals( 'ezcCacheStackLruReplacementStrategy', $opts->replacementStrategy );
        $this->assertEquals( false, $opts->bubbleUpOnRestore );
    }

    public function testGetFailure()
    {
        $opts = new ezcCacheStackOptions();
        try
        {
            echo $opts->fooBar;
            $ths->fail( 'Exception not thrown on access to unknown option.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ) {}
    }
}

?>
