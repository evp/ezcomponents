<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavLockDiscoveryPropertyActiveLockTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavLockDiscoveryPropertyActiveLock';
        $this->propertyName = 'activelock';
        $this->defaultValues = ['lockType'  => ezcWebdavLockRequest::TYPE_READ, 'lockScope' => ezcWebdavLockRequest::SCOPE_SHARED, 'depth'     => ezcWebdavRequest::DEPTH_INFINITY, 'owner'     => new ezcWebdavPotentialUriContent(), 'timeout'   => null, 'token'     => new ezcWebdavPotentialUriContent()];
        $this->workingValues = ['lockType' => [ezcWebdavLockRequest::TYPE_READ, ezcWebdavLockRequest::TYPE_WRITE], 'lockScope' => [ezcWebdavLockRequest::SCOPE_SHARED, ezcWebdavLockRequest::SCOPE_EXCLUSIVE], 'depth' => [ezcWebdavRequest::DEPTH_ZERO, ezcWebdavRequest::DEPTH_ONE, ezcWebdavRequest::DEPTH_INFINITY], 'owner' => [new ezcWebdavPotentialUriContent( '' ), new ezcWebdavPotentialUriContent( 'Foo Bar' ), new ezcWebdavPotentialUriContent( 'http://example.com', true )], 'timeout' => [null, 1, 23], 'token' => [new ezcWebdavPotentialUriContent( '' ), new ezcWebdavPotentialUriContent( 'foo bar' ), new ezcWebdavPotentialUriContent( 'http://example.com', true )]];
        $this->failingValues = ['lockType' => [23, 23.34, 'foobar', true, false, new stdClass(), []], 'lockScope' => [23, 23.34, 'foobar', true, false, new stdClass(), []], 'depth' => [23, 23.34, 'foobar', true, false, new stdClass(), []], 'owner' => [23, 23.34, true, false, new stdClass(), []], 'timeout' => [-23, 23.34, 'foobar', true, false, new stdClass(), []], 'token' => [23, 23.34, 'foobar', true, false, [], new stdClass()]];
        $this->alwaysHasContent = true;
    }
}

?>
