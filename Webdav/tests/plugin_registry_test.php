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
class ezcWebdavPluginRegistryTest extends ezcTestCase
{
    private static $beforeParams;

    private static $afterParams;

	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( self::class );
	}

    protected function setUp()
    {
        self::$beforeParams = null;
        self::$afterParams  = null;
    }

    public static function callbackBeforeTest( ezcWebdavPluginParameters $params )
    {
        self::$beforeParams = $params;
    }

    public static function callbackAfterTest( ezcWebdavPluginParameters $params )
    {
        self::$afterParams = $params;
    }

    public function testCtor()
    {
        $reg = new ezcWebdavPluginRegistry();

        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            [],
            'plugins',
            $reg,
            'Attribute $plugins not initialized correctly.'
        );

    }

    public function testRegisterPluginSuccess()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $reg->registerPlugin( $cfg );
        
        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            ['foonamespace' => $cfg],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             ['ezcWebdavTransport' => ['beforeParseRequest' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackBeforeTest'], [$cfg, 'testCallback']]], 'afterProcessResponse' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackAfterTest'], [$cfg, 'testCallback']]]]],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );
    }

    public function testRegisterPluginFailureDoubleRegister()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $reg->registerPlugin( $cfg );
        
        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            ['foonamespace' => $cfg],
            'plugins',
            $reg
        );

        try
        {
            $reg->registerPlugin( $cfg );
            $this->fail( 'Exception not thrown on double registered namespace.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testRegisterPluginFailureInvalidNamespace()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();
        $reg->namespace = true;

        $reg->registerPlugin( $cfg );
        
        try
        {
            $reg->registerPlugin( $cfg );
            $this->fail( 'Exception not thrown on double registered namespace.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testRegisterPluginFailureInvalidHooks()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $cfg->hooks = true;

        $reg = new ezcWebdavPluginRegistry();

        try
        {
            $reg->registerPlugin( $cfg );
            $this->fail( 'Exception not thrown on double registered namespace.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testRegisterPluginFailureInvalidHookClass()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $cfg->hooks = ['fooMyClass' => []];

        $reg = new ezcWebdavPluginRegistry();

        try
        {
            $reg->registerPlugin( $cfg );
            $this->fail( 'Exception not thrown on double registered namespace.' );
        }
        catch ( ezcWebdavInvalidHookException $e ) {}
    }

    public function testRegisterPluginFailureInvalidHook()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $cfg->hooks = ['ezcWebdavTransport' => ['beforeMyCustomHook' => []]];

        $reg = new ezcWebdavPluginRegistry();

        try
        {
            $reg->registerPlugin( $cfg );
            $this->fail( 'Exception not thrown on double registered namespace.' );
        }
        catch ( ezcWebdavInvalidHookException $e ) {}
    }

    public function testUnregisterPluginSuccess()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $reg->registerPlugin( $cfg );
        
        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            ['foonamespace' => $cfg],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             ['ezcWebdavTransport' => ['beforeParseRequest' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackBeforeTest'], [$cfg, 'testCallback']]], 'afterProcessResponse' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackAfterTest'], [$cfg, 'testCallback']]]]],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );

        $reg->unregisterPlugin( $cfg );

        $this->assertAttributeEquals(
            [],
            'plugins',
            $reg
        );
        
        $this->assertAttributeEquals(
             ['ezcWebdavTransport' => ['beforeParseRequest' => [], 'afterProcessResponse' => []]],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );
    }

    public function testUnregisterPluginFailureUnknown()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            [],
            'plugins',
            $reg
        );

        try
        {
            $reg->unregisterPlugin( $cfg );
            $this->fail( 'Exception not thrown on unregistering unknown namespace.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testUnregisterPluginFailureInvalidNamespace()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $cfg->namespace = true;
        $reg = new ezcWebdavPluginRegistry();
        
        try
        {
            $reg->unregisterPlugin( $cfg );
            $this->fail( 'Exception not thrown on unregistering invalid namespace.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }
    
    public function testGetPluginConfigSuccess()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $reg->registerPlugin( $cfg );
        
        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            ['foonamespace' => $cfg],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             ['ezcWebdavTransport' => ['beforeParseRequest' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackBeforeTest'], [$cfg, 'testCallback']]], 'afterProcessResponse' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackAfterTest'], [$cfg, 'testCallback']]]]],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );

        $this->assertEquals(
            $cfg,
            $reg->getPluginConfig( 'foonamespace' )
        );
    }
    
    public function testGetPluginConfigFailure()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            [],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             [],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );

        try
        {
            $reg->getPluginConfig( 'foonamespace' );
            $this->fail( 'Exception not thrown on get of unknown plugin namespace.' );
        }
        catch ( ezcBaseValueException $e ) {}

        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            [],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             [],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );
    }
    
    public function testHasPluginConfigSuccess()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $reg->registerPlugin( $cfg );
        
        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            ['foonamespace' => $cfg],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             ['ezcWebdavTransport' => ['beforeParseRequest' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackBeforeTest'], [$cfg, 'testCallback']]], 'afterProcessResponse' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackAfterTest'], [$cfg, 'testCallback']]]]],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );

        $this->assertTrue(
            $reg->hasPlugin( 'foonamespace' )
        );
    }
    
    public function testHasPluginConfigFailure()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            [],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             [],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );

        $this->assertFalse(
            $reg->hasPlugin( 'foonamespace' )
        );

        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            [],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             [],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );
    }
    
    public function testAnnounceHookSuccess()
    {
        $cfg = new fooCustomWebdavPluginConfiguration();
        $reg = new ezcWebdavPluginRegistry();

        $reg->registerPlugin( $cfg );
        
        $this->assertHooksCorrect( $reg );

        $this->assertAttributeEquals(
            ['foonamespace' => $cfg],
            'plugins',
            $reg
        );

        $this->assertAttributeEquals(
             ['ezcWebdavTransport' => ['beforeParseRequest' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackBeforeTest'], [$cfg, 'testCallback']]], 'afterProcessResponse' => ['foonamespace' => [['ezcWebdavPluginRegistryTest', 'callbackAfterTest'], [$cfg, 'testCallback']]]]],
            'assignedHooks',
            $reg,
            'Property $assignedHooks not set correctly after registration.'
        );

        $reg->announceHook( 'ezcWebdavTransport', 'beforeParseRequest', ( $beforeParams = new ezcWebdavPluginParameters() ) );

        $this->assertSame(
            $beforeParams,
            self::$beforeParams,
            'Params of before callback invalid'
        );

        $this->assertNull(
            self::$afterParams,
            'Params of after callback invalid'
        );

        $this->assertEquals(
            1,
            $cfg->callbackCalled,
            'Number of called callbackes invalid.'
        );

        $reg->announceHook( 'ezcWebdavTransport', 'afterProcessResponse', ( $afterParams = new ezcWebdavPluginParameters() ) );

        $this->assertSame(
            $beforeParams,
            self::$beforeParams,
            'Params of before callback invalid'
        );

        $this->assertEquals(
            new ezcWebdavPluginParameters(),
            self::$beforeParams,
            'Params of before callback invalid'
        );

        $this->assertSame(
            $afterParams,
            self::$afterParams,
            'Params of after callback invalid'
        );

        $this->assertEquals(
            2,
            $cfg->callbackCalled,
            'Number of called callbackes invalid.'
        );
    }


    protected function assertHooksCorrect( ezcWebdavPluginRegistry $reg )
    {
        $this->assertAttributeEquals(
            ['ezcWebdavTransport' => ['beforeParseRequest'     => true, 'afterProcessResponse'   => true, 'parseUnknownRequest'    => true, 'processUnknownResponse' => true], 'ezcWebdavPropertyHandler' => ['extractDeadProperty'          => true, 'serializeDeadProperty'        => true, 'extractUnknownLiveProperty'   => true, 'serializeUnknownLiveProperty' => true], 'ezcWebdavServer' => ['receivedRequest'   => true, 'generatedResponse' => true]],
            'hooks',
            $reg,
            'Attribute $hooks is invalid.'
        );
    }
}

?>
