<?php
/**
 * Basic test cases for the memory backend.
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
 * Custom classes to test inheritence. 
 */
require_once 'classes/foo_custom_classes.php';

/**
 * Tests for ezcWebdavServerConfiguration class.
 * 
 * @package Webdav
 * @subpackage Tests
 */
class ezcWebdavServerConfigurationTest extends ezcTestCase
{
	public static function suite()
	{
		return new PHPUnit_Framework_TestSuite( self::class );
	}

    public function testCtorSuccess()
    {
        $cfg = new ezcWebdavServerConfiguration();

        $this->assertAttributeEquals(
            ['userAgentRegex'       => '(.*)', 'transportClass'       => 'ezcWebdavTransport', 'xmlToolClass'         => 'ezcWebdavXmlTool', 'propertyHandlerClass' => 'ezcWebdavPropertyHandler', 'headerHandlerClass'   => 'ezcWebdavHeaderHandler', 'pathFactory'          => new ezcWebdavAutomaticPathFactory()],
            'properties',
            $cfg,
            'Default properties not created correctly on empty ctor.'
        );
        
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)'
        );

        $this->assertAttributeEquals(
            ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'ezcWebdavTransport', 'xmlToolClass'         => 'ezcWebdavXmlTool', 'propertyHandlerClass' => 'ezcWebdavPropertyHandler', 'headerHandlerClass'   => 'ezcWebdavHeaderHandler', 'pathFactory'          => new ezcWebdavAutomaticPathFactory()],
            'properties',
            $cfg,
            'Default properties not created correctly on empty ctor.'
        );
        
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'ezcWebdavCustomTransport'
        );

        $this->assertAttributeEquals(
            ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'ezcWebdavCustomTransport', 'xmlToolClass'         => 'ezcWebdavXmlTool', 'propertyHandlerClass' => 'ezcWebdavPropertyHandler', 'headerHandlerClass'   => 'ezcWebdavHeaderHandler', 'pathFactory'          => new ezcWebdavAutomaticPathFactory()],
            'properties',
            $cfg,
            'Default properties not created correctly on empty ctor.'
        );
        
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'fooCustomTransport',
            'fooCustomXmlTool'
        );

        $this->assertAttributeEquals(
            ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'fooCustomTransport', 'xmlToolClass'         => 'fooCustomXmlTool', 'propertyHandlerClass' => 'ezcWebdavPropertyHandler', 'headerHandlerClass'   => 'ezcWebdavHeaderHandler', 'pathFactory'          => new ezcWebdavAutomaticPathFactory()],
            'properties',
            $cfg,
            'Default properties not created correctly on empty ctor.'
        );
        
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'fooCustomTransport',
            'fooCustomXmlTool',
            'fooCustomPropertyHandler'
        );

        $this->assertAttributeEquals(
            ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'fooCustomTransport', 'xmlToolClass'         => 'fooCustomXmlTool', 'propertyHandlerClass' => 'fooCustomPropertyHandler', 'headerHandlerClass'   => 'ezcWebdavHeaderHandler', 'pathFactory'          => new ezcWebdavAutomaticPathFactory()],
            'properties',
            $cfg,
            'Default properties not created correctly on empty ctor.'
        );
        
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'fooCustomTransport',
            'fooCustomXmlTool',
            'fooCustomPropertyHandler',
            'fooCustomHeaderHandler'
        );

        $this->assertAttributeEquals(
            ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'fooCustomTransport', 'xmlToolClass'         => 'fooCustomXmlTool', 'propertyHandlerClass' => 'fooCustomPropertyHandler', 'headerHandlerClass'   => 'fooCustomHeaderHandler', 'pathFactory'          => new ezcWebdavAutomaticPathFactory()],
            'properties',
            $cfg,
            'Default properties not created correctly on empty ctor.'
        );
        
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'fooCustomTransport',
            'fooCustomXmlTool',
            'fooCustomPropertyHandler',
            'fooCustomHeaderHandler',
            new ezcWebdavBasicPathFactory( 'http://example.com' )
        );

        $this->assertAttributeEquals(
            ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'fooCustomTransport', 'xmlToolClass'         => 'fooCustomXmlTool', 'propertyHandlerClass' => 'fooCustomPropertyHandler', 'headerHandlerClass'   => 'fooCustomHeaderHandler', 'pathFactory'          => new ezcWebdavBasicPathFactory( 'http://example.com' )],
            'properties',
            $cfg,
            'Default properties not created correctly on empty ctor.'
        );
    }

    public function testCtorFailure()
    {
        $typicalFails = ['', 23, 23.42, true, false, [], new stdClass()];
        $typicalValid = 'fooSomeClass';

        $validCtorParams = [
            $typicalValid,
            // userAgentRegex
            $typicalValid,
            // transportClass
            $typicalValid,
            // xmlToolClass
            $typicalValid,
            // propertyHandlerClass
            $typicalValid,
            // headerHandlerClass
            new ezcWebdavAutomaticPathFactory(),
        ];

        $invalidCtorParams = [
            $typicalFails,
            // userAgentRegex
            $typicalFails,
            // transportClass
            $typicalFails,
            // xmlToolClass
            $typicalFails,
            // propertyHandlerClass
            $typicalFails,
            // headerHandlerClass
            array_merge( $typicalFails, ['foo'] ),
        ];

        foreach ( $invalidCtorParams as $id => $paramSet )
        {
            $params = [];
            for ( $i = 0; $i < $id; ++$i )
            {
                $params[$i] = $validCtorParams[$i];
            }
            foreach ( $paramSet as $param )
            {
                $params[$id] = $param;
                $this->assertCtorFailure( $params, ( $i !== 5 ? 'ezcBaseValueException' : 'PHPUnit_Framework_Error' ) );
            }
        }
    }

    public function testGetPropertiesDefaultSuccess()
    {
        $cfg = new ezcWebdavServerConfiguration();

        $defaults = ['userAgentRegex'       => '(.*)', 'transportClass'       => 'ezcWebdavTransport', 'xmlToolClass'         => 'ezcWebdavXmlTool', 'propertyHandlerClass' => 'ezcWebdavPropertyHandler', 'headerHandlerClass'   => 'ezcWebdavHeaderHandler', 'pathFactory'          => new ezcWebdavAutomaticPathFactory()];

        foreach ( $defaults as $property => $value )
        {
            $this->assertEquals(
                $value,
                $cfg->$property,
                "Property $property has incorrect default."
            );
        }
    }

    public function testGetPropertiesFromCtorSuccess()
    {
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'fooCustomTransport',
            'fooCustomXmlTool',
            'fooCustomPropertyHandler',
            'fooCustomHeaderHandler',
            new ezcWebdavBasicPathFactory( 'http://example.com' )
        );

        $values = ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'fooCustomTransport', 'xmlToolClass'         => 'fooCustomXmlTool', 'propertyHandlerClass' => 'fooCustomPropertyHandler', 'headerHandlerClass'   => 'fooCustomHeaderHandler', 'pathFactory'          => new ezcWebdavBasicPathFactory( 'http://example.com' )];

        foreach ( $values as $property => $value )
        {
            $this->assertEquals(
                $value,
                $cfg->$property,
                "Property $property has incorrect value after ctor setting."
            );
        }
    }

    public function testGetPropertiesFailure()
    {
        $cfg = new ezcWebdavServerConfiguration();

        try
        {
            echo $cfg->foo;
        }
        catch ( ezcBasePropertyNotFoundException $e )
        {
            return;
        }
        $this->fail( 'Property not thrown on get access of non-existent property.' );
    }

    public function testSetPropertiesGetPropertiesSuccess()
    {
        $cfg = new ezcWebdavServerConfiguration();

        $values = ['userAgentRegex'       => '(.*Nautilus.*)', 'transportClass'       => 'fooCustomTransport', 'xmlToolClass'         => 'fooCustomXmlTool', 'propertyHandlerClass' => 'fooCustomPropertyHandler', 'headerHandlerClass'   => 'fooCustomHeaderHandler', 'pathFactory'          => new ezcWebdavBasicPathFactory( 'http://example.com' )];

        foreach( $values as $property => $value )
        {
            $cfg->$property = $value;
        }

        $this->assertAttributeEquals(
            $values,
            'properties',
            $cfg
        );
        foreach ( $values as $property => $value )
        {
            $this->assertEquals(
                $value,
                $cfg->$property,
                "Property $property has incorrect value after ctor setting."
            );
        }
    }

    public function testSetAccessFailure()
    {
        $typicalFails = ['', 23, 23.42, true, false, [], new stdClass()];

        $invalidValues = ['userAgentRegex'       => $typicalFails, 'transportClass'       => $typicalFails, 'xmlToolClass'         => $typicalFails, 'propertyHandlerClass' => $typicalFails, 'headerHandlerClass'   => $typicalFails, 'pathFactory'          => array_merge( $typicalFails, ['foo'] )];

        foreach ( $invalidValues as $propertyName => $propertyValues )
        {
            $this->assertSetPropertyFailure( $propertyName, $propertyValues, 'ezcBaseValueException' );
        }

        try
        {
            $cfg = new ezcWebdavServerConfiguration();
            $cfg->fooBar = 23;
            $this->fail( 'Exception not thrown on set access to non-existent property.' );
        }
        catch ( ezcBasePropertyNotFoundException $e ){}
    }

    public function testPropertiesIssetAccessDefaultCtorSuccess()
    {
        $cfg = new ezcWebdavServerConfiguration();

        $properties =['userAgentRegex', 'transportClass', 'xmlToolClass', 'propertyHandlerClass', 'headerHandlerClass', 'pathFactory'];

        foreach( $properties as $propertyName )
        {
            $this->assertTrue(
                isset( $cfg->$propertyName ),
                "Property not set after default construction: '$propertyName'."
            );
        }
    }

    public function testPropertiesIssetAccessNonDefaultCtorSuccess()
    {
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'fooCustomTransport',
            'fooCustomXmlTool',
            'fooCustomPropertyHandler',
            'fooCustomHeaderHandler',
            new ezcWebdavBasicPathFactory( 'http://example.com' )
        );

        $properties =['userAgentRegex', 'transportClass', 'xmlToolClass', 'propertyHandlerClass', 'headerHandlerClass', 'pathFactory'];

        foreach( $properties as $propertyName )
        {
            $this->assertTrue(
                isset( $cfg->$propertyName ),
                "Property not set after default construction: '$propertyName'."
            );
        }
    }

    public function testPropertyIssetAccessFailure()
    {
        $cfg = new ezcWebdavServerConfiguration();

        $this->assertFalse(
            isset( $cfg->foo ),
            'Non-existent property $foo seems to be set.'
        );
        $this->assertFalse(
            isset( $cfg->properties ),
            'Non-existent property $properties seems to be set.'
        );
    }

    public function testGetTransportInstanceSuccessDefaultCtor()
    {
        $cfg = new ezcWebdavServerConfiguration();

        $server = ezcWebdavServer::getInstance();
        $cfg->configure( $server );

        $pathFactory     = new ezcWebdavAutomaticPathFactory();
        $xmlTool         = new ezcWebdavXmlTool();
        $propertyHandler = new ezcWebdavPropertyHandler();
        $transport       = new ezcWebdavTransport();

        $this->assertEquals(
            $xmlTool,
            $server->xmlTool
        );
        $this->assertEquals(
            $pathFactory,
            $server->pathFactory
        );
        $this->assertEquals(
            $propertyHandler,
            $server->propertyHandler
        );
        $this->assertEquals(
            $transport,
            $server->transport
        );
    }

    public function testGetTransportInstanceSuccessNonDefaultCtor()
    {
        $cfg = new ezcWebdavServerConfiguration(
            '(.*Nautilus.*)',
            'fooCustomTransport',
            'fooCustomXmlTool',
            'fooCustomPropertyHandler',
            'fooCustomHeaderHandler',
            new ezcWebdavBasicPathFactory( 'http://foo.example.com/webdav/' )
        );
        
        $server = ezcWebdavServer::getInstance();
        $cfg->configure( $server );

        $xmlTool         = new fooCustomXmlTool();
        $pathFactory     = new ezcWebdavBasicPathFactory( 'http://foo.example.com/webdav/' );
        $propertyHandler = new fooCustomPropertyHandler();
        $headerHandler   = new fooCustomHeaderHandler();
        $transport       = new fooCustomTransport();

        $this->assertEquals(
            $xmlTool,
            $server->xmlTool
        );
        $this->assertEquals(
            $pathFactory,
            $server->pathFactory
        );
        $this->assertEquals(
            $propertyHandler,
            $server->propertyHandler
        );
        $this->assertEquals(
            $transport,
            $server->transport
        );
    }

    protected function assertCtorFailure( array $args, $exceptionClass )
    {
        try
        {
            $cfgClass = new ReflectionClass( 'ezcWebdavServerConfiguration' );
            $cfg = $cfgClass->newInstanceArgs( $args );
        }
        catch( Exception $e )
        {
            ( !( $e instanceof $exceptionClass ) ? var_dump( $e ) : null );
            $this->assertTrue(
                ( $e instanceof $exceptionClass ),
                "Exception thrown on invalid value set of wrong exception class. '" . get_class( $e ) . "' instead of '$exceptionClass'."
            );
            return;
        }
        $this->fail( "Exception not thrown on invalid argument set." );
    }

    protected function assertSetPropertyFailure( $propertyName, array $propertyValues, $exceptionClass )
    {
        foreach ( $propertyValues as $value )
        {
            try
            {
                $cfg = new ezcWebdavServerConfiguration();
                $cfg->$propertyName = $value;
                $this->fail( "Exception not thrown on invalid ___set() value for property '$propertyName'." );
            }
            catch( Exception $e )
            {
                $this->assertTrue(
                    ( $e instanceof $exceptionClass ),
                    "Exception thrown on invalid value set for property '$propertyName'. '" . get_class( $e ) . "' instead of '$exceptionClass'."
                );
            }
        }
    }
}

?>
