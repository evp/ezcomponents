<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavGetContentTypePropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavGetContentTypeProperty';
        $this->propertyName = 'getcontenttype';
        $this->defaultValues = ['mime'    => null, 'charset' => null];
        $this->workingValues = ['mime' => [null, "foo bar", ""], 'charset' => [null, "foo bar", ""]];
        $this->failingValues = ['mime' => [23, 23.34, true, false, new stdClass(), []], 'charset' => [23, 23.34, true, false, new stdClass(), []]];
    }
}

?>
