<?php
/**
 * File containing the ezcWebdavFileBackendOptionsTestCase class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @copyright Copyright (C) 2005-2010 eZ Systems AS. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 * @subpackage Test
 */

require_once __DIR__ . '/property_test.php';

/**
 * Test case for the ezcWebdavFileBackendOptions class.
 * 
 * @package Webdav
 * @version 1.1.4
 * @subpackage Test
 */
class ezcWebdavServerOptionsTest extends ezcWebdavPropertyTestCase
{
    public static function suite()
    {
		return new PHPUnit_Framework_TestSuite( self::class );
    }

    protected function setUp()
    {
        $this->className = 'ezcWebdavServerOptions';
        $this->defaultValues = ['realm' => 'eZ Components WebDAV'];
        $this->workingValues = ['realm' => ['Some nice realm.', '    ', '23', '']];
        $this->failingValues = ['realm' => [null, true, false, 23, -42.23, [], new stdClass()]];
    }

    public function testCtorSuccess()
    {
        $class = new ReflectionClass( $this->className );
        $object = $class->newInstance();

        $this->assertPropertyValues( $object, $this->defaultValues );
    }
}

?>
