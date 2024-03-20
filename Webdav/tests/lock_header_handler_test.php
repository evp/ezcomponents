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
class ezcWebdavLockHeaderHandlerTest extends ezcTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        ezcWebdavServer::getInstance()->init(
            new ezcWebdavBasicPathFactory( 'http://example.com' ),
            new ezcWebdavXmlTool(),
            new ezcWebdavPropertyHandler(),
            new ezcWebdavHeaderHandler(),
            new ezcWebdavTransport()
        );
    }

    protected function tearDown()
    {
        ezcWebdavServer::getInstance()->reset();
    }
    
    /**
     * testParseNoTaggedList 
     * 
     * @param mixed $content 
     * @param mixed $result 
     * @return void
     *
     * @dataProvider provideIfHeaderData
     */
    public function testParseIfHeader( $content, $result )
    {
        $_SERVER['HTTP_IF'] = $content;
        
        $handler = new ezcWebdavLockHeaderHandler();
        
        $this->assertEquals(
            $result,
            $handler->parseIfHeader()
        );
    }

    /**
     * testParseTimeoutHeader 
     * 
     * @param mixed $content 
     * @param mixed $result 
     * @return void
     *
     * @dataProvider provideTimeoutHeaderData
     */
    public function testParseTimeoutHeader( $content, $result )
    {
        $_SERVER['HTTP_TIMEOUT'] = $content;
        
        $handler = new ezcWebdavLockHeaderHandler();
        
        $this->assertEquals(
            $result,
            $handler->parseTimeoutHeader()
        );
    }

    public function provideTimeoutHeaderData()
    {
        return [
            // Set 1 - Ususally expected
            ['Second-23', [23]],
            // Set 2 - Also expected
            ['Infinite, Second-23', [23]],
            // Set 3 - May occur
            ['Infinite, Second-123456789, Second-23', [123456789, 23]],
        ];
    }

    public function provideIfHeaderData()
    {
        return [
            // Not tagged
            ['(<locktoken:a-write-lock-token> [W/"A weak ETag"]) (["strong ETag"]) (["another strong ETag"])', new ezcWebdavLockIfHeaderNoTagList(
                [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'locktoken:a-write-lock-token' )],
                    [new ezcWebdavLockIfHeaderCondition( 'A weak ETag' )]
                ), new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'strong ETag' )]
                ), new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'another strong ETag' )]
                )]
            )],
            // Not tagged, mutiple lock tokens, from RFC
            ['(<opaquelocktoken:fe184f2e-6eec-41d0-c765-01adc56e6bb4>)  (<opaquelocktoken:e454f3f3-acdc-452a-56c7-00a5c91e4b77>)', new ezcWebdavLockIfHeaderNoTagList(
                [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'opaquelocktoken:fe184f2e-6eec-41d0-c765-01adc56e6bb4' )],
                    []
                ), new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'opaquelocktoken:e454f3f3-acdc-452a-56c7-00a5c91e4b77' )],
                    []
                )]
            )],
            // Not tagged, negated some
            ['(Not <locktoken:a-write-lock-token> [W/"A weak ETag"]) (["strong ETag"]) (Not ["another strong ETag"])', new ezcWebdavLockIfHeaderNoTagList(
                [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'locktoken:a-write-lock-token', true )],
                    [new ezcWebdavLockIfHeaderCondition( 'A weak ETag' )]
                ), new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'strong ETag' )]
                ), new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'another strong ETag', true )]
                )]
            )],
            // Not tagged, lock token and etag, from Litmus
            ['(<opaquelocktoken:43e241e1-df33-d3ee-bbfc-c613148efeb0> [fdf78d927cbf3fac5929db44c91d5783])', new ezcWebdavLockIfHeaderNoTagList(
                [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'opaquelocktoken:43e241e1-df33-d3ee-bbfc-c613148efeb0' )],
                    [new ezcWebdavLockIfHeaderCondition( 'fdf78d927cbf3fac5929db44c91d5783' )]
                )]
            )],
            // Tagged
            ['<http://example.com/resource1> (<locktoken:a-write-lock-token> [W/"A weak ETag"]) (["strong ETag"]) <http://example.com/random> (["another strong ETag"])', new ezcWebdavLockIfHeaderTaggedList(
                ['/resource1' => [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'locktoken:a-write-lock-token' )],
                    [new ezcWebdavLockIfHeaderCondition( 'A weak ETag' )]
                ), new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'strong ETag' )]
                )], '/random' => [new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'another strong ETag' )]
                )]]
            )],
            // Tagged, negated some
            ['<http://example.com/resource1> (<locktoken:a-write-lock-token> [W/"A weak ETag"]) (Not ["strong ETag"]) <http://example.com/random> (Not ["another strong ETag"])', new ezcWebdavLockIfHeaderTaggedList(
                ['/resource1' => [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'locktoken:a-write-lock-token' )],
                    [new ezcWebdavLockIfHeaderCondition( 'A weak ETag' )]
                ), new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'strong ETag', true )],
                    true
                )], '/random' => [new ezcWebdavLockIfHeaderListItem(
                    [],
                    [new ezcWebdavLockIfHeaderCondition( 'another strong ETag', true )]
                )]]
            )],
            ['<http://webdav/collection/newdir/> (<opaquelocktoken:e0491761-ef66-9c09-94be-b43d185e2ad3>) <http://webdav/collection/subdir/> (<opaquelocktoken:2e5dba96-db89-da63-e87e-f9688848a315>)', new ezcWebdavLockIfHeaderTaggedList(
                ['/collection/newdir' => [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'opaquelocktoken:e0491761-ef66-9c09-94be-b43d185e2ad3' )]
                )], '/collection/subdir' => [new ezcWebdavLockIfHeaderListItem(
                    [new ezcWebdavLockIfHeaderCondition( 'opaquelocktoken:2e5dba96-db89-da63-e87e-f9688848a315' )]
                )]]
            )],
        ];
    }
}

?>
