<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavResourceTypePropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavResourceTypeProperty';
        $this->propertyName = 'resourcetype';
        $this->defaultValues = ['type' => null];
        $this->workingValues = ['type' => [null, ezcWebdavResourceTypeProperty::TYPE_COLLECTION, ezcWebdavResourceTypeProperty::TYPE_RESSOURCE]];
        $this->failingValues = ['type' => [23, 23.34, '', 'foo', true, false, [23, 42], new stdClass()]];
    }
}

?>
