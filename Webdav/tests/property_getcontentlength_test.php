<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavGetContentLengthPropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavGetContentLengthProperty';
        $this->propertyName = 'getcontentlength';
        $this->defaultValues = ['length' => null];
        $this->workingValues = ['length' => [
            null,
            "1234",
            // 2 GB + 1b
            "2147483649",
        ]];
        $this->failingValues = ['length' => ['foo', 23, 23.34, true, false, new stdClass()]];
    }
}

?>
