<?php
/**
 * File containing the ezcWebdavLockPluginOptionsTest class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @subpackage Test
 */


/**
 * Test case for the ezcWebdavLockPluginOptions class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @subpackage Test
 */
class ezcWebdavLockPluginOptionsTest extends ezcTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testConstructorSuccess()
    {
        $opt = new ezcWebdavLockPluginOptions();

        $this->assertAttributeEquals(
            ['lockTimeout'         => 900, 'backendLockTimeout'  => 10000000, 'backendLockWaitTime' => 10000],
            'properties',
            $opt
        );

        $opt = new ezcWebdavLockPluginOptions(
            ['lockTimeout'         => 123, 'backendLockTimeout'  => 123456, 'backendLockWaitTime' => 1234]
        );

        $this->assertAttributeEquals(
            ['lockTimeout'         => 123, 'backendLockTimeout'  => 123456, 'backendLockWaitTime' => 1234],
            'properties',
            $opt
        );
    }

    public function testSetAccessSuccess()
    {
        $opt = new ezcWebdavLockPluginOptions();

        $this->assertSetProperty(
            $opt,
            'lockTimeout',
            [1, 23, 42, 100000, 2147483647]
        );
        $this->assertSetProperty(
            $opt,
            'backendLockTimeout',
            [1, 23, 42, 100000, 2147483647]
        );
        $this->assertSetProperty(
            $opt,
            'backendLockWaitTime',
            [1, 23, 42, 100000, 2147483647]
        );
    }

    public function testSetAccessFailure()
    {
        $opt = new ezcWebdavLockPluginOptions();

        $this->assertSetPropertyFails(
            $opt,
            'lockTimeout',
            [0, -42, true, false, 'foo', 23.42, [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opt,
            'backendLockTimeout',
            [0, -42, true, false, 'foo', 23.42, [], new stdClass()]
        );
        $this->assertSetPropertyFails(
            $opt,
            'backendLockWaitTime',
            [0, -42, true, false, 'foo', 23.42, [], new stdClass()]
        );
    }

    public function testGetAccessFailure()
    {
        $opt = new ezcWebdavLockPluginOptions();

        try
        {
            echo $opt->foo;
            $this->fail( 'Exception not thrown on get access to non-existent property.' );
        }
        catch( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testIssetAccess()
    {
        $opt = new ezcWebdavLockPluginOptions();

        $this->assertTrue(
            isset( $opt->lockTimeout )
        );
        $this->assertTrue(
            isset( $opt->backendLockTimeout )
        );
        $this->assertTrue(
            isset( $opt->backendLockWaitTime )
        );
        $this->assertFalse(
            isset( $opt->foo )
        );
    }
}

?>
