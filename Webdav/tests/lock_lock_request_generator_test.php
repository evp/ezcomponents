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
class ezcWebdavLockLockRequestGeneratorTest extends ezcTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }

    public function testConstructor()
    {
        $lockReq = new ezcWebdavLockRequest(
            '/some/path'
        );
        $activeLock = new ezcWebdavLockDiscoveryPropertyActiveLock();

        $reqGen = new ezcWebdavLockLockRequestGenerator(
            $lockReq,
            $activeLock
        );

        $this->assertAttributeSame(
            $lockReq,
            'issueingRequest',
            $reqGen
        );
        $this->assertAttributeSame(
            $activeLock,
            'activeLock',
            $reqGen
        );
        $this->assertAttributeEquals(
            [],
            'requests',
            $reqGen
        );
    }

    public function testNotifyPropertyNotAvailable()
    {
        // Setup request generator
        $lockReq = new ezcWebdavLockRequest(
            '/some/path'
        );
        $activeLock = new ezcWebdavLockDiscoveryPropertyActiveLock();

        $reqGen = new ezcWebdavLockLockRequestGenerator(
            $lockReq,
            $activeLock
        );

        // Fake PROPFIND response
        $propStatRes = new ezcWebdavPropStatResponse(
            new ezcWebdavBasicPropertyStorage(),
            ezcWebdavResponse::STATUS_200
        );
        $propStatRes->storage->attach(
            new ezcWebdavGetEtagProperty()
        );
        $propFindRes = new ezcWebdavPropFindResponse(
            new ezcWebdavResource( '/some/path' ),
            $propStatRes
        );
        
        // Fake result
        $result = new ezcWebdavPropPatchRequest( '/some/path' );
        $result->updates->attach(
            new ezcWebdavLockDiscoveryProperty(
                new ArrayObject( 
                    [$activeLock]
                )
            ),
            ezcWebdavPropPatchRequest::SET
        );

        // Perform notify
        $reqGen->notify( $propFindRes );

        $this->assertAttributeEquals(
            [$result],
            'requests',
            $reqGen
        );
    }

    public function testNotifyPropertyAvailable()
    {
        // Setup request generator
        $lockReq = new ezcWebdavLockRequest(
            '/some/path'
        );
        $activeLock = new ezcWebdavLockDiscoveryPropertyActiveLock();

        $reqGen = new ezcWebdavLockLockRequestGenerator(
            $lockReq,
            $activeLock
        );

        // Fake PROPFIND response
        $lockDiscoveryProperty =  new ezcWebdavLockDiscoveryProperty(
            new ArrayObject(
                [new ezcWebdavLockDiscoveryPropertyActiveLock(
                    ezcWebdavLockRequest::TYPE_WRITE,
                    ezcWebdavLockRequest::SCOPE_EXCLUSIVE,
                    ezcWebdavRequest::DEPTH_INFINITY,
                    'somone@example.com'
                )]
            )
        );

        $propStatRes = new ezcWebdavPropStatResponse(
            new ezcWebdavBasicPropertyStorage(),
            ezcWebdavResponse::STATUS_200
        );
        $propStatRes->storage->attach(
            new ezcWebdavGetEtagProperty()
        );
        $propStatRes->storage->attach(
            $lockDiscoveryProperty
        );
        $propFindRes = new ezcWebdavPropFindResponse(
            new ezcWebdavResource( '/some/path' ),
            $propStatRes
        );
        
        // Fake result
        $newLockDiscoveryProperty = clone $lockDiscoveryProperty;
        $newLockDiscoveryProperty->activeLock->append( $activeLock );
        $result = new ezcWebdavPropPatchRequest( '/some/path' );
        $result->updates->attach(
            $newLockDiscoveryProperty,
            ezcWebdavPropPatchRequest::SET
        );

        // Perform notify
        $reqGen->notify( $propFindRes );

        $this->assertAttributeEquals(
            [$result],
            'requests',
            $reqGen
        );
    }

    public function testNotifyPropertyNotFound()
    {
        // Setup request generator
        $lockReq = new ezcWebdavLockRequest(
            '/some/path'
        );
        $activeLock = new ezcWebdavLockDiscoveryPropertyActiveLock();

        $reqGen = new ezcWebdavLockLockRequestGenerator(
            $lockReq,
            $activeLock
        );

        // Fake PROPFIND response
        $propStatRes = new ezcWebdavPropStatResponse(
            new ezcWebdavBasicPropertyStorage(),
            ezcWebdavResponse::STATUS_200
        );
        $propStatRes->storage->attach(
            new ezcWebdavGetEtagProperty()
        );
        $propStatRes2 = new ezcWebdavPropStatResponse(
            new ezcWebdavBasicPropertyStorage(),
            ezcWebdavResponse::STATUS_404
        );
        $propStatRes2->storage->attach(
            new ezcWebdavLockDiscoveryProperty()
        );
        $propFindRes = new ezcWebdavPropFindResponse(
            new ezcWebdavResource( '/some/path' ),
            $propStatRes,
            $propStatRes2
        );
        
        // Fake result
        $result = new ezcWebdavPropPatchRequest( '/some/path' );
        $result->updates->attach(
            new ezcWebdavLockDiscoveryProperty(
                new ArrayObject( 
                    [$activeLock]
                )
            ),
            ezcWebdavPropPatchRequest::SET
        );

        // Perform notify
        $reqGen->notify( $propFindRes );

        $this->assertAttributeEquals(
            [$result],
            'requests',
            $reqGen
        );
    }
}

?>
