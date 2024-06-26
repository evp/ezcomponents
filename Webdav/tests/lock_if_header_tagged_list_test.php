<?php
/**
 * File containing the ezcWebdavFileBackendOptionsTestCase class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @subpackage Test
 */

require_once __DIR__ . '/property_test.php';

/**
 * Test case for the ezcWebdavFileBackendOptions class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @subpackage Test
 */
class ezcWebdavLockIfHeaderTaggedListTest extends ezcTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    public function testConstructor()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        $this->assertAttributeEquals(
            [],
            'items',
            $list
        );
    }

    public function testOffsetSetSuccess()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        $item1 = [new ezcWebdavLockIfHeaderListItem()];
        $item2 = [new ezcWebdavLockIfHeaderListItem()];

        $list['/some/path'] = $item1;
        $list['/'] = $item2;

        $this->assertAttributeEquals(
            ['/some/path' => $item1, '/' => $item2],
            'items',
            $list
        );
    }

    public function testOffsetSetFailure()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        try
        {
            $list['/some/path'] = 23;
            $this->fail( 'Exception not thrown on invalid value.' );
        }
        catch ( ezcBaseValueException $e ) {}

        try
        {
            $list['/'] = new stdClass();
            $this->fail( 'Exception not thrown on invalid value.' );
        }
        catch ( ezcBaseValueException $e ) {}

        try
        {
            $list[''] = new ezcWebdavLockIfHeaderListItem();
            $this->fail( 'Exception not thrown on invalid offset.' );
        }
        catch ( ezcBaseValueException $e ) {}

        try
        {
            $list[23] = new ezcWebdavLockIfHeaderListItem();
            $this->fail( 'Exception not thrown on invalid offset.' );
        }
        catch ( ezcBaseValueException $e ) {}

        $this->assertAttributeEquals(
            [],
            'items',
            $list
        );
    }

    public function testOffsetGetSuccess()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        $item1 = [new ezcWebdavLockIfHeaderListItem()];
        $item2 = [new ezcWebdavLockIfHeaderListItem()];

        $list['/some/path'] = $item1;
        $list['/'] = $item2;
        
        $this->assertEquals(
            $item1,
            $list['/some/path']
        );
        $this->assertEquals(
            $item2,
            $list['/']
        );
        $this->assertEquals(
            [],
            $list['/non/existent']
        );
    }

    public function testOffsetGetFailure()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        try
        {
            $list[''];
            $this->fail( 'Exception not thrown on invalid offset.' );
        }
        catch ( ezcBaseValueException $e ) {}

        try
        {
            $list[23];
            $this->fail( 'Exception not thrown on invalid value.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testOffsetIssetSuccess()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        $item1 = [new ezcWebdavLockIfHeaderListItem()];
        $item2 = [new ezcWebdavLockIfHeaderListItem()];

        $list['/some/path'] = $item1;
        $list['/'] = $item2;

        $this->assertTrue(
            isset( $list['/'] )
        );
        $this->assertTrue(
            isset( $list['/some/path'] )
        );
        $this->assertFalse(
            isset( $list['/none/existent'] )
        );
    }

    public function testOffsetIssetFailure()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        try
        {
            isset( $list[''] );
            $this->fail( 'Exception not thrown on invalid offset.' );
        }
        catch ( ezcBaseValueException $e ) {}

        try
        {
            isset( $list[23] );
            $this->fail( 'Exception not thrown on invalid value.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testOffsetUnsetSuccess()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        $item1 = [new ezcWebdavLockIfHeaderListItem()];
        $item2 = [new ezcWebdavLockIfHeaderListItem()];

        $list['/some/path'] = $item1;
        $list['/'] = $item2;

        $this->assertTrue(
            isset( $list['/'] )
        );
        $this->assertTrue(
            isset( $list['/some/path'] )
        );
        $this->assertFalse(
            isset( $list['/none/existent'] )
        );

        unset( $list['/'] );
        unset( $list['/some/path'] );
        unset( $list['/none/existent'] );

        $this->assertFalse(
            isset( $list['/'] )
        );
        $this->assertFalse(
            isset( $list['/some/path'] )
        );
        $this->assertFalse(
            isset( $list['/none/existent'] )
        );
    }

    public function testOffsetUnsetFailure()
    {
        $list = new ezcWebdavLockIfHeaderTaggedList();

        try
        {
            unset( $list[''] );
            $this->fail( 'Exception not thrown on invalid offset.' );
        }
        catch ( ezcBaseValueException $e ) {}

        try
        {
            unset( $list[23] );
            $this->fail( 'Exception not thrown on invalid value.' );
        }
        catch ( ezcBaseValueException $e ) {}
    }

    public function testGetLockTokens()
    {
        $item1 = new ezcWebdavLockIfHeaderListItem(
            [new ezcWebdavLockIfHeaderCondition( 'lock-token-1' ), new ezcWebdavLockIfHeaderCondition( 'lock-token-2', true ), new ezcWebdavLockIfHeaderCondition( 'lock-token-3' )],
            [new ezcWebdavLockIfHeaderCondition( 'etag-1', true ), new ezcWebdavLockIfHeaderCondition( 'etag-2', true ), new ezcWebdavLockIfHeaderCondition( 'etag-3' )]
        );
        $item2 = new ezcWebdavLockIfHeaderListItem(
            [new ezcWebdavLockIfHeaderCondition( 'lock-token-1' ), new ezcWebdavLockIfHeaderCondition( 'lock-token-4' )],
            [new ezcWebdavLockIfHeaderCondition( 'etag-1' ), new ezcWebdavLockIfHeaderCondition( 'etag-4', true ), new ezcWebdavLockIfHeaderCondition( 'etag-5' )]
        );
        $item3 = new ezcWebdavLockIfHeaderListItem(
            [new ezcWebdavLockIfHeaderCondition( 'lock-token-5', true ), new ezcWebdavLockIfHeaderCondition( 'lock-token-6', true )],
            []
        );

        $list = new ezcWebdavLockIfHeaderTaggedList();

        $list['/'] = [$item2];
        $list['/some/path'] = [$item1, $item3];
        $list['/other/path'] = [$item3, $item2];

        $this->assertEquals(
            [0 => 'lock-token-1', 1 => 'lock-token-4', 3 => 'lock-token-2', 4 => 'lock-token-3', 5 => 'lock-token-5', 6 => 'lock-token-6'],
            $list->getLockTokens()
        );
    }
}

?>
