<?php
/**
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @version 1.7.1
 * @filesource
 * @package PersistentObject
 * @subpackage Tests
 */

/**
 * Tests the load facilities of ezcPersistentIdenitySessionOptions.
 *
 * @package PersistentObject
 * @subpackage Tests
 */
class ezcPersistentSessionIdentityDecoratorOptionsTest extends ezcTestCase
{
    public static function suite()
    {
        return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testCtorNoArgs()
    {
        $opts = new ezcPersistentSessionIdentityDecoratorOptions();

        $this->assertFalse(
            $opts->refetch
        );
    }

    public function testCtorArgs()
    {
        $opts = new ezcPersistentSessionIdentityDecoratorOptions(
            ['refetch' => true]
        );

        $this->assertTrue(
            $opts->refetch
        );
    }

    public function testGetAccessSuccess()
    {
        $opts = new ezcPersistentSessionIdentityDecoratorOptions();

        $this->assertFalse(
            $opts->refetch
        );
    }

    public function testGetAccessFailure()
    {
        $opts = new ezcPersistentSessionIdentityDecoratorOptions();

        try
        {
            echo $opts->fooBar;
            $this->fail( 'Exception not thrown on get access to non-existent property.' );
        } catch ( ezcBasePropertyNotFoundException $e ) {}
    }

    public function testIssetAccess()
    {
        $opts = new ezcPersistentSessionIdentityDecoratorOptions();

        $this->assertTrue( isset( $opts->refetch ) );
        $this->assertFalse( isset( $opts->fooBar ) );
    }
    
    public function testSetAccessSuccess()
    {
        $opts = new ezcPersistentSessionIdentityDecoratorOptions();

        $this->assertSetProperty(
            $opts,
            'refetch',
            [true, false]
        );
    }
    
    public function testSetAccessFailure()
    {
        $opts = new ezcPersistentSessionIdentityDecoratorOptions();

        $this->assertSetPropertyFails(
            $opts,
            'refetch',
            [null, 23, 42.23, 'foo', [], new stdClass()]
        );
    }
}

?>
