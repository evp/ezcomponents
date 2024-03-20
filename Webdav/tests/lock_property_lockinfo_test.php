<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavLockInfoPropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavLockInfoProperty';
        $this->propertyName = 'lockinfo';
        $this->namespace = ezcWebdavLockInfoProperty::NAMESPACE;
        $this->defaultValues = ['tokenInfo' => new ArrayObject(), 'null' => false];
        $this->workingValues = ['tokenInfo' => [new ArrayObject( [23, 42] ), new ArrayObject( ['foo', 'bar'] )], 'null' => [true, false]];
        $this->failingValues = ['tokenInfo' => [null, new stdClass(), [], true, false, 23, 42.23, 'foo'], 'null' => [null, new stdClass(), [], 23, 42.23, 'foo']];
    }
}

?>
