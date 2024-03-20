<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavSupportedLockPropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavSupportedLockProperty';
        $this->propertyName = 'supportedlock';
        $this->defaultValues = ['lockEntries' => new ArrayObject()];
        $this->workingValues = ['lockEntries' => [new ArrayObject(), new ArrayObject(
            [new ezcWebdavSupportedLockPropertyLockentry(), new ezcWebdavSupportedLockPropertyLockentry()]
        )]];
        $this->failingValues = ['lockEntries' => [23, 23.34, 'foobar', true, false, [], new stdClass()]];
    }
}

?>
