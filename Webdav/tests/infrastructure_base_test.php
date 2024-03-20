<?php
/**
 * Test case for the ezcWebdavInfrastructureBase class.
 *
 * @package Webdav
 * @subpackage Tests
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

/**
 * Mock class to remove "abstract".
 * 
 * @package Webdav
 * @version 1.1.4
 */
class fooCustomWebdavInfrastructure extends ezcWebdavInfrastructureBase {}

/**
 * Reqiuire base test
 */

/**
 * Require mocked version of ezcWebdavPluginConfiguration. 
 */
require_once 'classes/custom_plugin_configuration.php';

/**
 * Tests for ezcWebdavInfrastructureBase class.
 * 
 * @package Webdav
 * @subpackage Tests
 */
class ezcWebdavInfrastructureBaseTest extends ezcTestCase
{
    protected $namespaces = [];

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( self::class );
	}

    protected function setUp()
    {
        $srv = ezcWebdavServer::getInstance();

        $this->namespaces['foonamespace'] = new fooCustomWebdavPluginConfiguration();

        $this->namespaces['namespacebar'] = new fooCustomWebdavPluginConfiguration();
        $this->namespaces['namespacebar']->namespace = 'namespacebar';

        $srv->pluginRegistry->registerPlugin( $this->namespaces['foonamespace'] );
        $srv->pluginRegistry->registerPlugin( $this->namespaces['namespacebar'] );
    }

    protected function tearDown()
    {
        $srv = ezcWebdavServer::getInstance();

        $srv->pluginRegistry->unregisterPlugin( $this->namespaces['foonamespace'] );
        $srv->pluginRegistry->unregisterPlugin( $this->namespaces['namespacebar'] );
    }

    public function testSetPluginDataSuccess()
    {
        $base = new fooCustomWebdavInfrastructure();
        
        $base->setPluginData( 'foonamespace', 'barkey', [23, 42] );

        $this->assertAttributeEquals(
            ['foonamespace' => ['barkey' => [23, 42]]],
            'pluginData',
            $base,
            'Plugin data not set correctly.'
        );
        
        $base->setPluginData( 'foonamespace', 'bazkey', true );

        $this->assertAttributeEquals(
            ['foonamespace' => ['barkey' => [23, 42], 'bazkey' => true]],
            'pluginData',
            $base,
            'Plugin data not set correctly.'
        );
        
        $base->setPluginData( 'namespacebar', 'keyfoo', new stdClass() );

        $this->assertAttributeEquals(
            ['foonamespace' => ['barkey' => [23, 42], 'bazkey' => true], 'namespacebar' => ['keyfoo' => new stdClass()]],
            'pluginData',
            $base,
            'Plugin data not set correctly.'
        );
    }

    public function testSetPluginDataFailure()
    {
        $base = new fooCustomWebdavInfrastructure();

        try
        {
            $base->setPluginData( 'foonamespace', 23, 'foo' );
            $this->fail( 'Exception not thrown on integer key.' );
        }
        catch ( ezcBaseValueException $e ) {}

        /*
        try
        {
            $base->setPluginData( 'unknown namespace', 'bar', 'foo' );
            $this->fail( 'Exception not thrown on integer key.' );
        }
        catch ( ezcBaseValueException $e ) {}
        */
    }

    public function testRemovePluginDataSuccess()
    {
        $base = new fooCustomWebdavInfrastructure();

        $base->setPluginData( 'foonamespace', 'barkey', [23, 42] );
        $base->setPluginData( 'foonamespace', 'bazkey', true );
        $base->setPluginData( 'namespacebar', 'keyfoo', new stdClass() );

        $this->assertAttributeEquals(
            ['foonamespace' => ['barkey' => [23, 42], 'bazkey' => true], 'namespacebar' => ['keyfoo' => new stdClass()]],
            'pluginData',
            $base,
            'Plugin data not unset correctly.'
        );

        $base->removePluginData( 'foonamespace', 'barkey' );

        $this->assertAttributeEquals(
            ['foonamespace' => ['bazkey' => true], 'namespacebar' => ['keyfoo' => new stdClass()]],
            'pluginData',
            $base,
            'Plugin data not unset correctly.'
        );

        $base->removePluginData( 'namespacebar', 'keyfoo' );

        $this->assertAttributeEquals(
            ['foonamespace' => ['bazkey' => true], 'namespacebar' => []],
            'pluginData',
            $base,
            'Plugin data not unset correctly.'
        );

        $base->removePluginData( 'foonamespace', 'bazkey' );

        $this->assertAttributeEquals(
            ['foonamespace' => [], 'namespacebar' => []],
            'pluginData',
            $base,
            'Plugin data not unset correctly.'
        );
    }

    public function testRemovePluginDataFailure()
    {
        $base = new fooCustomWebdavInfrastructure();

        $base->setPluginData( 'foonamespace', 'barkey', [23, 42] );
        $base->setPluginData( 'foonamespace', 'bazkey', true );
        $base->setPluginData( 'namespacebar', 'keyfoo', new stdClass() );

        try
        {
            $base->removePluginData( 'unkown namespace', 'unknown key' );
            $this->fail( 'Exception not thrown on removal of data from unknown plugin namespace.' );
        }
        catch ( ezcBaseValueException $e ) {}

        $this->assertAttributeEquals(
            ['foonamespace' => ['barkey' => [23, 42], 'bazkey' => true], 'namespacebar' => ['keyfoo' => new stdClass()]],
            'pluginData',
            $base,
            'Plugin data not unset correctly.'
        );
    }
}



?>
