<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavSourcePropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavSourceProperty';
        $this->propertyName = 'source';
        $this->defaultValues = ['links' => []];
        $this->workingValues = ['links' => [[], [new ezcWebdavSourcePropertyLink(), new ezcWebdavSourcePropertyLink()]]];
        $this->failingValues = ['links' => [23, 23.34, 'foobar', true, false, new stdClass()]];
    }
}

?>
