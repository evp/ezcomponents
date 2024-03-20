<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavGetLastModifiedPropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavGetLastModifiedProperty';
        $this->propertyName = 'getlastmodified';
        $this->defaultValues = ['date' => null];
        $this->workingValues = ['date' => [null, new ezcWebdavDateTime( "+3 hours" )]];
        $this->failingValues = ['date' => [23, 23.34, 'foobar', true, false, [23, 42], new stdClass()]];
    }
}

?>
