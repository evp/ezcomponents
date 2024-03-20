<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavGetContentLanguagePropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavGetContentLanguageProperty';
        $this->propertyName = 'getcontentlanguage';
        $this->defaultValues = ['languages' => []];
        $this->workingValues = ['languages' => [[], ['en'], ['en', 'de', 'no', 'nl']]];
        $this->failingValues = ['languages' => [null, 'foo', 23, 23.34, true, false, new stdClass()]];
    }
}

?>
