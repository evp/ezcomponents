<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavDisplayNamePropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavDisplayNameProperty';
        $this->propertyName = 'displayname';
        $this->defaultValues = ['displayName' => null];
        $this->workingValues = ['displayName' => [null, '', 'Foo Bar Baz']];
        $this->failingValues = ['displayName' => [23, 23.34, true, false, [23, 42], new stdClass()]];
    }
}

?>
