<?php

require_once __DIR__ . '/webdav_property_test.php';

class ezcWebdavDeadPropertyTest extends ezcWebdavWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    /**
     * Get object of $className for testing.
     * 
     * @return stdClass
     */
    protected function getObject()
    {
        return new $this->className( 'namespace', 'name' );
    }

    public function testCtorSuccess()
    {
        return true;
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavDeadProperty';
        $this->propertyName = 'name';
        $this->namespace = 'namespace';
        $this->defaultValues = ['content'    => null];
        $this->workingValues = ['content' => [null, "foo bar", ""]];
        $this->failingValues = ['content' => [23, 23.34, true, false, new stdClass(), []]];
    }
}

?>
