<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavSourcePropertyLinkTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavSourcePropertyLink';
        $this->propertyName = 'link';
        $this->defaultValues = ['src' => null, 'dst' => null];
        $this->workingValues = ['src' => [null, '', 'foobar'], 'dst' => [null, '', 'foobar']];
        $this->failingValues = ['src' => [23, 23.34, true, false, new stdClass(), []], 'dst' => [23, 23.34, true, false, new stdClass(), []]];
    }
}

?>
