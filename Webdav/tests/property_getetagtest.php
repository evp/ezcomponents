<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavGetEtagPropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavGetEtagProperty';
        $this->propertyName = 'getetag';
        $this->defaultValues = ['etag'    => null];
        $this->workingValues = ['etag' => [null, "foo bar", ""]];
        $this->failingValues = ['etag' => [23, 23.34, true, false, new stdClass(), []]];
    }
}

?>
