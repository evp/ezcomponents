<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavLockDiscoveryPropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavLockDiscoveryProperty';
        $this->propertyName = 'lockdiscovery';
        $this->defaultValues = ['activeLock' => new ArrayObject()];
        $this->workingValues = ['activeLock' => [new ArrayObject(), new ArrayObject(
            [new ezcWebdavLockDiscoveryPropertyActiveLock(), new ezcWebdavLockDiscoveryPropertyActiveLock()]
        )]];
        $this->failingValues = ['activeLock' => [23, 23.34, 'foobar', true, false, new stdClass(), []]];
    }
}

?>
