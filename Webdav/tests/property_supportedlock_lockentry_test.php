<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavSupportedLockPropertyLockentryTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavSupportedLockPropertyLockentry';
        $this->propertyName = 'lockentry';
        $this->defaultValues = ['lockType'  => ezcWebdavLockRequest::TYPE_READ, 'lockScope' => ezcWebdavLockRequest::SCOPE_SHARED];
        $this->workingValues = ['lockType' => [ezcWebdavLockRequest::TYPE_READ, ezcWebdavLockRequest::TYPE_WRITE], 'lockScope' => [ezcWebdavLockRequest::SCOPE_SHARED, ezcWebdavLockRequest::SCOPE_EXCLUSIVE]];
        $this->failingValues = ['lockType' => [23, 23.34, 'foobar', true, false, new stdClass(), []], 'lockScope' => [23, 23.34, 'foobar', true, false, new stdClass(), []]];
        $this->alwaysHasContent = true;
    }
}

?>
