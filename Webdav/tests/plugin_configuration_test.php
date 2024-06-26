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
class ezcWebdavPluginConfigurationTest extends ezcTestCase
{
	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( self::class );
	}

    public function testGetHooks()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $this->assertEquals(
            ['ezcWebdavTransport' => ['beforeParseRequest' => [['ezcWebdavPluginRegistryTest', 'callbackBeforeTest'], [$cfg, 'testCallback']], 'afterProcessResponse' => [['ezcWebdavPluginRegistryTest', 'callbackAfterTest'], [$cfg, 'testCallback']]]],
            $cfg->getHooks()
        );
    }

    public function testGetNamespace()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $this->assertEquals(
            'foonamespace',
            $cfg->getNamespace()
        );
    }

    public function testInit()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $cfg->init();
        $this->assertTrue(
            $cfg->init
        );
    }
}

?>
